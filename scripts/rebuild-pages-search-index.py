#!/usr/bin/env python3
from __future__ import annotations

import argparse
import json
import re
from html.parser import HTMLParser
from pathlib import Path


class TextExtractor(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.title_parts: list[str] = []
        self.body_parts: list[str] = []
        self._in_title = False
        self._main_depth = 0
        self._skip_depth = 0

    def handle_starttag(self, tag: str, attrs) -> None:
        if tag == "title":
            self._in_title = True
        if tag == "main":
            self._main_depth += 1
        if tag in {"script", "style", "svg"}:
            self._skip_depth += 1

    def handle_endtag(self, tag: str) -> None:
        if tag == "title":
            self._in_title = False
        if tag == "main" and self._main_depth:
            self._main_depth -= 1
        if tag in {"script", "style", "svg"} and self._skip_depth:
            self._skip_depth -= 1

    def handle_data(self, data: str) -> None:
        text = data.strip()
        if not text:
            return
        if self._in_title:
            self.title_parts.append(text)
            return
        if self._skip_depth or not self._main_depth:
            return
        self.body_parts.append(text)


def clean_text(value: str) -> str:
    return re.sub(r"\s+", " ", value).strip()


def page_url(path: Path, root: Path, prefix: str) -> str:
    relative = path.relative_to(root).as_posix()
    if relative == "index.html":
        return prefix or "/"
    if relative.endswith("/index.html"):
        relative = relative[: -len("index.html")]
    return "/" + "/".join(part for part in [prefix.strip("/"), relative.strip("/")] if part)


def build_index(root: Path, locale: str, prefix: str) -> list[dict[str, object]]:
    pages: list[dict[str, object]] = []
    locale_root = root / locale
    for html_path in sorted(locale_root.rglob("index.html")):
        text = html_path.read_text(encoding="utf-8", errors="ignore")
        extractor = TextExtractor()
        extractor.feed(text)
        title = clean_text(" ".join(extractor.title_parts))
        if " | " in title:
            title = title.split(" | ", 1)[1].strip() or title
        content = clean_text(" ".join(extractor.body_parts))
        if not title and not content:
            continue
        pages.append(
            {
                "url": page_url(html_path, root, prefix),
                "title": title,
                "lang": locale,
                "content": content,
                "headings": [],
            }
        )
    return pages


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--root", default="build_production")
    parser.add_argument("--locale", default="en")
    parser.add_argument("--prefix", default="")
    args = parser.parse_args()

    root = Path(args.root)
    pages = build_index(root, args.locale, args.prefix)
    output = root / f"search-index_{args.locale}.json"
    output.write_text(json.dumps(pages, ensure_ascii=False, indent=4), encoding="utf-8")
    print(f"Wrote {output} with {len(pages)} pages")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

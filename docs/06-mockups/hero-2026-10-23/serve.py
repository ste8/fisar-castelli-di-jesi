#!/usr/bin/env python3
"""Serve solo il provino e gli asset necessari, non il repository intero."""

from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer
from pathlib import Path
from urllib.parse import unquote, urlsplit


PREVIEW = Path(__file__).resolve().parent
PROJECT = PREVIEW.parents[2]
PREVIEW_URL = "/docs/06-mockups/hero-2026-10-23/"
GLASS_URL = "/plugin/fisar-cdj-core/assets/demo/hero-calice-scontornato-fisar-cdj-v2.webp"


class PreviewHandler(SimpleHTTPRequestHandler):
    def translate_path(self, path):
        requested = unquote(urlsplit(path).path)
        if requested == GLASS_URL:
            return str(PROJECT / requested.lstrip("/"))

        routes = (
            ("/theme/fisar-cdj/assets/", PROJECT / "theme/fisar-cdj/assets"),
            (PREVIEW_URL, PREVIEW),
            ("/", PREVIEW),
        )
        for prefix, root in routes:
            if requested.startswith(prefix):
                candidate = (root / requested[len(prefix):]).resolve()
                if candidate == root or root in candidate.parents:
                    return str(candidate)
                break
        return str(PREVIEW / ".not-served")


if __name__ == "__main__":
    server = ThreadingHTTPServer(("127.0.0.1", 8092), PreviewHandler)
    print(f"Confronto locale: http://127.0.0.1:8092{PREVIEW_URL}", flush=True)
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        pass
    finally:
        server.server_close()

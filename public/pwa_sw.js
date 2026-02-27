const CACHE_VERSION = "paw-tpi-v1";
const APP_SHELL = [
  "/",
  "/manifest.json",
  "/assets/styles/global.css",
  "/assets/styles/header.css",
  "/assets/styles/footer.css",
  "/assets/logo.png",
  "/assets/logo_positivo.png",
  "/assets/icono.ico"
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_VERSION).then((cache) => cache.addAll(APP_SHELL))
  );
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== CACHE_VERSION)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener("fetch", (event) => {
  if (event.request.method !== "GET") return;

  const accept = event.request.headers.get("accept") || "";

  if (accept.includes("text/html") || accept.includes("application/json")) {
    event.respondWith(fetch(event.request));
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cached) => cached || fetch(event.request))
  );
});
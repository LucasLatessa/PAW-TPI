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

self.addEventListener("install", () => {
  self.skipWaiting();
});

self.addEventListener("activate", () => {
  self.clients.claim();
});

self.addEventListener("fetch", (event) => {
  if (event.request.method !== "GET") return;

  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) return cached;

      return fetch(event.request)
        .then((response) => {
          const clone = response.clone();
          caches.open(CACHE_VERSION).then((cache) => cache.put(event.request, clone));
          return response;
        })
        .catch(() => caches.match("/"));
    })
  );
});

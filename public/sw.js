const CACHE_NAME = 'caribetour-cache-v1';
const urlsToCache = [
  '/',
  '/css/style.css',
  '/images/favicon.svg',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'
];

// Instalación: Guarda los archivos en la caché
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

// Estrategia: "Network First, falling back to cache"
// Intenta descargar lo más nuevo, si falla (offline), usa la caché.
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request);
    })
  );
});
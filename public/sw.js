/**
 * CaribeTour — Service Worker
 * - Precache robusto (fallos por URL no bloquean el resto)
 * - activate: limpia cachés antiguas + clients.claim
 * - Navegación: red primero; si offline, intenta documento en caché (/)
 * - Estáticos same-origin (/css, /images, /js, /fonts): stale-while-revalidate
 * - Orígenes cruzados (CDN): sin interceptar (comportamiento por defecto del navegador)
 */

const VERSION = 'v2';
const STATIC_CACHE = `caribetour-static-${VERSION}`;

const PRECACHE_URLS = [
  '/',
  '/css/style.css',
  '/images/favicon.svg',
  '/images/logo.png',
  '/manifest.json',
];

self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(precacheUrls(PRECACHE_URLS));
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    (async () => {
      const keys = await caches.keys();
      await Promise.all(
        keys
          .filter((key) => key.startsWith('caribetour-') && key !== STATIC_CACHE)
          .map((key) => caches.delete(key)),
      );
      await self.clients.claim();
    })(),
  );
});

/**
 * @param {string[]} urls
 */
async function precacheUrls(urls) {
  const cache = await caches.open(STATIC_CACHE);
  await Promise.all(
    urls.map(async (url) => {
      try {
        const response = await fetch(url, {
          cache: 'reload',
          credentials: 'same-origin',
        });
        if (response.ok) {
          await cache.put(url, response.clone());
        }
      } catch (e) {
        console.warn('[SW] Precache omitido:', url, e);
      }
    }),
  );
}

/**
 * @param {FetchEvent} event
 * @param {Request} request
 * @returns {Promise<Response>}
 */
async function staleWhileRevalidate(event, request) {
  const cache = await caches.open(STATIC_CACHE);
  const cached = await cache.match(request);

  const networkPromise = fetch(request).then(async (response) => {
    if (response.ok && request.method === 'GET') {
      await cache.put(request, response.clone());
    }
    return response;
  });

  if (cached) {
    event.waitUntil(
      networkPromise.catch(() => {
        /* red falló; se sigue sirviendo caché */
      }),
    );
    return cached;
  }

  try {
    return await networkPromise;
  } catch {
    return new Response('Sin conexión', {
      status: 503,
      statusText: 'Service Unavailable',
      headers: { 'Content-Type': 'text/plain; charset=UTF-8' },
    });
  }
}

/**
 * @param {URL} url
 */
function isSameOrigin(url) {
  return url.origin === self.location.origin;
}

/**
 * @param {string} pathname
 */
function isStaticAssetPath(pathname) {
  return (
    pathname.startsWith('/css/') ||
    pathname.startsWith('/js/') ||
    pathname.startsWith('/images/') ||
    pathname.startsWith('/fonts/') ||
    pathname.endsWith('.woff2')
  );
}

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  const url = new URL(event.request.url);

  if (!isSameOrigin(url)) {
    return;
  }

  if (url.pathname === '/sw.js') {
    return;
  }

  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((response) => response)
        .catch(async () => {
          const fallback = await caches.match('/');
          if (fallback) {
            return fallback;
          }
          return new Response('Sin conexión', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: { 'Content-Type': 'text/plain; charset=UTF-8' },
          });
        }),
    );
    return;
  }

  if (isStaticAssetPath(url.pathname)) {
    event.respondWith(staleWhileRevalidate(event, event.request));
  }
});

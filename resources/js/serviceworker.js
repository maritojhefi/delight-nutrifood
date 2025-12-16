var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    "/offline",
    "/styles/bootstrap.css",
    "/styles/highlights/highlight_mint.css",
    "/scripts/bootstrap.min.js",
    "/scripts/custom.js",
    //'/productos',
    "/images/icons/icon-72x72.png",
    "/images/icons/icon-96x96.png",
    "/images/icons/icon-128x128.png",
    "/images/icons/icon-144x144.png",
    "/images/icons/icon-152x152.png",
    "/images/icons/icon-192x192.png",
    "/images/icons/icon-384x384.png",
    "/images/icons/icon-512x512.png",
];

// Cache on install
self.addEventListener("install", (event) => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then((cache) => {
            return cache.addAll(filesToCache);
        })
    );
});

// Clear cache on activate
self.addEventListener("activate", (event) => {
    event.waitUntil(
        Promise.all([
            // Limpiar caches viejos
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((cacheName) => cacheName.startsWith("pwa-"))
                        .filter((cacheName) => cacheName !== staticCacheName)
                        .map((cacheName) => caches.delete(cacheName))
                );
            }),
            // Forzar que este SW tome control inmediatamente
            self.clients.claim(),
        ])
    );
});

// Serve from Cache
self.addEventListener("fetch", (event) => {
    const url = new URL(event.request.url);

    // Si el recurso tiene query string de versión (?v=), usar Network First (no cachear)
    // Esto evita que el SW sirva versiones viejas de JS/CSS cuando cambias la versión
    if (url.searchParams.has("v") || url.search.includes("?v=")) {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    // No cachear recursos versionados, siempre ir a la red
                    return response;
                })
                .catch(() => {
                    // Solo si falla la red, intentar desde cache como último recurso
                    return caches
                        .match(event.request)
                        .then((cachedResponse) => {
                            return cachedResponse || caches.match("/offline");
                        });
                })
        );
        return;
    }

    // Para recursos sin versión, usar Cache First (comportamiento normal)
    event.respondWith(
        caches
            .match(event.request)
            .then((response) => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match("offline");
            })
    );
});

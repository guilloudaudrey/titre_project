//service-worker.js

/**
var version = 'v1::';

self.addEventListener("install", function (event) {
    try {

        console.log('WORKER: install event in progress.');
        event.waitUntil(
            caches
                .open(version + 'fundamentals')
                .then(function (cache) {
                    return cache.addAll([
                        '/',
                        '/service-worker.js'

                    ]);
                })
                .then(function () {
                    console.log('WORKER: install completed');
                })
        );

    }
    catch (error) {
        console.log(error);
    }
});

**/

/**

var cache_name = 'typo';
var cached_urls = [
    '/',
    '/profile',
    '/register',
    '/login'



];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(cache_name)
            .then(function(cache) {
                return cache.addAll(cached_urls);
            })
    );
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName. ('typo')) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', function(event) {
    console.log('Fetch event for ', event.request.url);
    event.respondWith(
        caches.match(event.request).then(function(response) {
            if (response) {
                console.log('Found ', event.request.url, ' in cache');
                return response;
            }
            console.log('Network request for ', event.request.url);
            return fetch(event.request).then(function(response) {

                return caches.open(cached_urls).then(function(cache) {
                    cache.put(event.request.url, response.clone());
                    return response;
                });
            });
        }).catch(function(error) {
            console.log('Error, ', error);
            return caches.match('offline.html');
        })
    );
});

**/

var cacheName = 'blog-1';
var dataCacheName = 'data-blog-1';
var filesToCache = [
    '/app.php',




];

self.addEventListener('install', function(e) {
    console.log('[ServiceWorker] Install');
    e.waitUntil(
        caches.open(cacheName).then(function(cache) {
            console.log('[ServiceWorker] Caching app shell');
            return cache.addAll(filesToCache);
        })

    );
});
self.addEventListener('activate', function(e) {
    console.log('[ServiceWorker] Activate');
    e.waitUntil(
        caches.keys().then(function(keyList) {
            return Promise.all(keyList.map(function(key) {
                if (key !== cacheName && key !== dataCacheName) {
                    console.log('[ServiceWorker] Removing old cache', key);
                    return caches.delete(key);
                }
            }));
        })
    );
    return self.clients.claim();
});

self.addEventListener('fetch', function(e) {
    console.log('[ServiceWorker] Fetch', e.request.url);
    var dataUrl = '/api';
    if (e.request.url.indexOf(dataUrl) > -1) {
        /*
         * When the request URL contains dataUrl, the app is asking for fresh
         * weather data. In this case, the service worker always goes to the
         * network and then caches the response. This is called the "Cache then
         * network" strategy:
         * https://jakearchibald.com/2014/offline-cookbook/#cache-then-network
         */
        e.respondWith(
            caches.open(dataCacheName).then(function(cache) {
                return fetch(e.request).then(function(response){
                    cache.put(e.request.url, response.clone());
                    return response;
                });
            })
        );
    } else {
        /*
         * The app is asking for app shell files. In this scenario the app uses the
         * "Cache, falling back to the network" offline strategy:
         * https://jakearchibald.com/2014/offline-cookbook/#cache-falling-back-to-network
         */
        e.respondWith(
            caches.match(e.request).then(function(response) {
                return response || fetch(e.request);
            })
        );
    }
});


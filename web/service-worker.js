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

var cache_name = 'typo';
var cached_urls = [
    '/',
    './profile',
    './register',



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
                    if (cacheName.startsWith('pages-cache-') && staticCacheName !== cacheName) {
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
                if (response.status === 404) {
                    return caches.match('fourohfour.html');
                }
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


/**

self.addEventListener('install', function(event) {
    console.log('Installed service-worker.js', event);
});

self.addEventListener('activate', function(event) {
    console.log('Activated service-worker.js\', event);\n' +
        '});.js', event);
});


var cacheName = 'typo';
var filesToCache = [

];
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(cacheName)
            .then(function(cache) {
                console.info('[sw.js] cached all files');
                return cache.addAll(filesToCache);
            })
    );
});
**/


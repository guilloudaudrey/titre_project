//service-worker.js
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
                        'http://localhost:8000/',
                        'http://localhost:8000/post',
                        'http://localhost:8000/assets/css/main.css'

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
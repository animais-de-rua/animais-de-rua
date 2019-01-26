const VERSION = '0.0.9';
const CACHE = 'adr';

let cacheAssets = [
	// Dynamic Assets
	'/sw.js?id=' + VERSION,
	'/js/app.js?id=d92dac58f1f283ca13f7',
	'/css/app.css?id=82bbbb7a6b40d467b8e7',
	'/fonts/icomoon.woff2?58c2d240bb8f452ceba24cac9e697d8e',

	// Other Assets	
	'/manifest.json',
	'/img/logo/logo.svg',

	// Main images of each page
	'/img/animals.webp',
	'/img/association.webp',
	'/img/ced.webp',
	'/img/friend.webp',
	'/img/help.webp',
	'/img/home.webp',
	'/img/partners.webp'
];

let cachePages = [
	'/blank',
	'/?ajax',
	'/association?ajax',
	'/ced?ajax',
	'/animals?ajax',
	'/help?ajax',
	'/partners?ajax',
	'/friends?ajax',
	'/privacy-policy?ajax',
];

self.oninstall = e => {
	self.skipWaiting();

	e.waitUntil(caches.open(CACHE).then(function (cache) {
		cache.addAll(cachePages);
		cache.addAll(cacheAssets);
	}));
}

self.onactivate = e => {
	e.waitUntil(self.clients.claim());
}

self.onfetch = e => {
	// Avoid chrome strange error
	if (e.request.cache === 'only-if-cached' && e.request.mode !== 'same-origin')
		return;

	// Avoid admin and store areas
	if(e.request.url.match(/\/admin|\/store|\/vendor|\/pdf/)) {
		console.log("Avoided: " + e.request.url);
		return;
	}

	// Avoid external requests
	if(!e.request.url.match(/animaisderua.org/))
		return;

	// If it's a page access, retreive blank
	if (e.request.destination == 'document') {

		// Update cache if language was changed
		if(e.request.url.match(/\/lang\/[a-z]{2}$/)) {
			caches.open(CACHE).then(function (cache) {
				cachePages.forEach(page => cache.delete(page));
				cache.addAll(cachePages);
			});
		}

		// Online
		if(navigator.onLine) {
			e.respondWith(fetch(e.request));

			// Update blank cache
			fetch('/blank').then(response =>
				caches.open(CACHE).then(cache => {
					cache.put('/blank', response.clone())
				})
			);

			return;
		}

		// Offline
		else {
			return e.respondWith(
				caches.match('/blank', {ignoreSearch: true}).then(response => response)
			);
		}
	}
	
	// Update css and js cache
	if(e.request.url.match(/\.(css|js|webp)/)) {
		caches.open(CACHE).then(cache => {
			cache.match(e.request, {ignoreSearch: false}).then(response => {
				if(!response) {
					cache.delete(e.request.url, {ignoreSearch: true});
					fetch(e.request).then(response => cache.put(e.request, response.clone()));
				}
			});
		})
	}

	// Allways try to respond with cache
	e.respondWith(
		caches.match(e.request, {ignoreSearch: false}).then(response => {
			return response || fetch(e.request);
		})
	);
}

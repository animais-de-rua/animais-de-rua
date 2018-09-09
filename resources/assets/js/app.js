
// ----------
// Load Modules

window.utils = require('./utils.js');


// ----------
// Main Code
document.addEventListener('DOMContentLoaded', e => {

});

let navbar = document.querySelector('.navbar'),
	navbarCards = document.querySelector('.navbar .cards'),
	navbarMobile = document.querySelector('.navbar .mobile'),
	navbarMobileCards = document.querySelector('.navbar .mobile-card-view');

mobileMenu = e => {
	if(navbar.classList.contains('card-view'))
		return navbar.classList.remove('card-view');

	e.classList.toggle('active');
	navbarMobile.classList.toggle('active');
};

mobileMenuCard = i => {
	navbar.classList.toggle('card-view');

	navbarMobileCards.children.map(card => card.style.display = 'none');
	navbarMobileCards.children[i].style.display = 'block';
}

menuCard = (e, i) => {
	if(e.classList.contains('active'))
		return e.classList.remove('active');

	navbarCards.children.map(card => card.classList.remove('active'));
	e.classList.add('active');
}
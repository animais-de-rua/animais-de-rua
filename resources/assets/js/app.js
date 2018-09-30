// Load Modules
import * as utils from './utils.js';

const loader = document.getElementById('loading'),
    content = document.getElementById('content'),
    navbarElem = document.getElementById('navbar'),
    navbarCards = navbarElem.querySelectorAll('.cards > .card'),
    navbarMobile = navbarElem.querySelector('.mobile'),
    navbarMobileMenu = navbarElem.querySelector('.menu'),
    navbarMobileCards = navbarElem.querySelectorAll('.mobile-card-view > .menu-panel');

window.router = {
    init: e => {
        window.history.pushState({'html': content.innerHTML}, '', location.pathname);

        window.onpopstate = e => {
            if(e.state) {
                content.innerHTML = e.state.html;
                app.onReload();
            }
        }
    },

    push: (content, urlPath) => {
        window.history.pushState({'html': content}, '', urlPath);
    }
}

window.navbar = {
    init: e => {
        // Close navbar cards when clicking out of them
        content.addEventListener('click', e => {
            if(navbarElem.querySelector('.card.active'))
                navbarElem.querySelector('.card.active').classList.remove('active');
        });

        // Simple Handler for touch on mobile menu
        let startTouch;
        navbarMobile.addEventListener("touchstart", e => { startTouch = e.changedTouches[0] }, {passive: true});
        navbarMobile.addEventListener("touchend", e => {
            let [diffX, diffY] = [
                e.changedTouches[0].clientX - startTouch.clientX,
                e.changedTouches[0].clientY - startTouch.clientY
            ];

            if((navbarElem.classList.contains("card-view") && diffY > 60) || diffX < -60)
                navbarMobileMenu.click();
        }, {passive: true});
    },

    onMobileMenuClick: e => {
        if(navbarElem.classList.contains('card-view'))
            return navbarElem.classList.remove('card-view');

        e.classList.toggle('active');
        navbarMobile.classList.toggle('active');
        document.body.classList.toggle('overflow');
    },

    onMobileCardClick: i => {
        navbarElem.classList.toggle('card-view');

        navbarMobileCards.forEach(card => card.style.display = 'none');
        navbarMobileCards[i].style.display = 'block';
    },

    onCardClick: (e, i) => {
        if(e.classList.contains('active'))
            return e.classList.remove('active');

        navbarCards.forEach(card => card.classList.remove('active'));
        e.classList.add('active');
    }
}

window.sliders = {
    init: e => {
        document.querySelectorAll('.flex-slider').forEach(elem => {
            sliders.start(elem);

            // Dots
            elem.querySelectorAll('.dots > li').forEach(dot => {
                dot.addEventListener('click', e => {
                    // Index of this element in parent
                    let index = utils.indexOf(dot);
                    let slider = dot.closest('.flex-slider');

                    // Restart slider interval
                    sliders.start(slider);

                    sliders.moveTo(slider, index);
                })
            });
        });
    },

    start: slider => {
        let autoScroll = slider.getAttribute('auto-scroll');

        if(autoScroll) {
            let interval = slider.getAttribute('interval');
            if(interval)
                clearInterval(interval);

            slider.setAttribute('interval', setInterval(e => {
                sliders.move(slider);
            }, autoScroll));
        }
    },

    move: slider => {
        let dots = slider.querySelector('.dots');
        let index = utils.indexOf(dots.querySelector('.active'));

        index++;
        if(index == dots.children.length)
            index = 0;

        sliders.moveTo(slider, index);
    },

    moveTo: (slider, index) => {
        // Change active Dot
        let dots = slider.querySelector('.dots');
        dots.querySelector('.active').classList.remove('active');
        dots.children[index].classList.add('active');

        // Add the translate
        slider.querySelector('ul').style = "transform: translateX(-" + index * 100 + "%);";
    }
}

window.accordions = {
    init: e => {
        document.querySelectorAll('.accordion > h1').forEach(elem => {
            elem.addEventListener('click', e => {
                e.target.parentElement.classList.toggle('open');
            });
        });
    }
}

window.loading = {
    start: e => loader.className = 'start',
    end: e => loader.className = 'end'
}

window.app = {
    onReload: e => {
        // AJAX Links controller
        document.querySelectorAll('a.link').forEach(link => {
            link.addEventListener('click', e => {
                let urlPath = e.target.href;

                loading.start();
                content.classList.remove('anim');

                e.preventDefault();
                fetch(urlPath, {
                    credentials: 'same-origin',
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                }).then(response => {
                    // Closes mobile menu
                    if(navbarMobile.classList.contains('active')) {
                        navbarElem.querySelector('.menu').click();
                        // In case second menu is oppened
                        if(navbarMobile.classList.contains('active'))
                            setTimeout(e => navbarElem.querySelector('.menu').click(), 250);
                    }

                    response.text().then(html => {
                        content.innerHTML = html;
                        app.onReload();

                        loading.end();
                        content.classList.add('anim');

                        router.push(html, urlPath);
                    })
                }).catch(e => {
                    window.location = urlPath;
                });
            })
        });

        // Stop propagation links
        document.querySelectorAll('.stopPropagation').forEach(e => e.addEventListener('click', e => e.stopPropagation()));

        // Sliders
        sliders.init();

        // Accordion
        accordions.init();
    }
}

document.addEventListener('DOMContentLoaded', e => {
    app.onReload();

    router.init();
    navbar.init();
});
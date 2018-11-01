// Load Modules
import * as utils from './utils.js';

const _loading = document.getElementById('loading'),
    _content = document.getElementById('content'),
    _navbar = document.getElementById('navbar');

window.router = {
    init: e => {
        window.history.pushState({'html': _content.innerHTML}, '', location.pathname);

        window.onpopstate = e => {
            if(e.state) {
                _content.innerHTML = e.state.html;
                app.init();
            }
        }
    },

    push: (content, urlPath) => {
        window.history.pushState({'html': content}, '', urlPath);
    }
}

window.navbar = {
    init: e => {
        this.navbarCards = _navbar.querySelectorAll('.cards > .card');
        this.navbarMobile = _navbar.querySelector('.mobile');
        this.navbarMobileMenu = _navbar.querySelector('.menu');
        this.navbarMobileCards = _navbar.querySelectorAll('.mobile-card-view > .menu-panel');

        // Close navbar cards when clicking out of them
        _content.addEventListener('click', e => {
            if(_navbar.querySelector('.card.active'))
                _navbar.querySelector('.card.active').classList.remove('active');
        });

        // Simple Handler for touch on mobile menu
        let startTouch;
        this.navbarMobile.addEventListener("touchstart", e => { startTouch = e.changedTouches[0] }, {passive: true});
        this.navbarMobile.addEventListener("touchend", e => {
            let [diffX, diffY] = [
                e.changedTouches[0].clientX - startTouch.clientX,
                e.changedTouches[0].clientY - startTouch.clientY
            ];

            if((_navbar.classList.contains("card-view") && diffY > 60) || diffX < -60)
                this.navbarMobileMenu.click();
        }, {passive: true});
    },

    close: e => {
        if(this.navbarMobile.classList.contains('active')) {
            this.navbarMobileMenu.click();
            // In case second menu is oppened
            if(this.navbarMobile.classList.contains('active'))
                setTimeout(e => _navbar.querySelector('.menu').click(), 250);
        }
    },

    onMobileMenuClick: e => {
        if(_navbar.classList.contains('card-view'))
            return _navbar.classList.remove('card-view');

        e.classList.toggle('active');
        this.navbarMobile.classList.toggle('active');
        document.body.classList.toggle('overflow');
    },

    onMobileCardClick: i => {
        _navbar.classList.toggle('card-view');

        this.navbarMobileCards.forEach(card => card.style.display = 'none');
        this.navbarMobileCards[i].style.display = 'block';
    },

    onCardClick: (e, i) => {
        if(e.classList.contains('active'))
            return e.classList.remove('active');

        this.navbarCards.forEach(card => card.classList.remove('active'));
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

window.isotope = {
    init: e => {
        document.querySelectorAll('.isotope select').forEach(elem => {
            elem.addEventListener('change', e => {
                let filters = [];
                let isotope = e.target.closest('.isotope');

                isotope.querySelectorAll('select').forEach(select => {
                    if(select.value)
                        filters.push('[' + select.getAttribute('type') + '~="' + select.value + '"]');
                });

                isotope.querySelectorAll('.box').forEach(box => box.classList.remove('active'));
                isotope.querySelectorAll('.box' + filters.join('')).forEach(box => box.classList.add('active'));


                isotope.querySelector('.empty').style.display = isotope.querySelectorAll('.box.active').length ? "none" : "block";
            });
        });
    }
}

window.loading = {
    start: e => _loading.className = 'start',
    end: e => _loading.className = 'end'
}

window.app = {
    init: e => {
        // AJAX Links controller
        document.querySelectorAll('a.link').forEach(link => {
            link.classList.remove('link');
            link.addEventListener('click', e => {
                let urlPath = e.target.closest('a').href;

                window.scrollTo(0, 0);
                loading.start();
                _content.classList.remove('anim');

                e.preventDefault();
                fetch(urlPath, {
                    credentials: 'same-origin',
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                }).then(response => {
                    // Closes mobile menu
                    navbar.close();

                    response.text().then(html => {
                        _content.innerHTML = html;
                        app.init();

                        loading.end();
                        _content.classList.add('anim');

                        router.push(html, urlPath);
                    })
                }).catch(e => {
                    window.location = urlPath;
                });
            })
        });

        // Stop propagation links
        document.querySelectorAll('.stopPropagation').forEach(e => {
            e.classList.remove('stopPropagation');
            e.addEventListener('click', e => e.stopPropagation())
        });

        // Sliders
        sliders.init();

        // Accordion
        accordions.init();

        // Isotope
        isotope.init();
    },

    onModalitiesClick: e => {
        let index = utils.indexOf(e.parentElement);
        let form =  document.querySelector('.modalities form');
        form.querySelector('select > option:nth-child(' + (index + 1) + ')').selected = true;
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', e => {
    app.init();
    router.init();
    navbar.init();
});
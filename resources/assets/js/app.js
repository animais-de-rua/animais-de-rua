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
        this.navbarSwipeable = _navbar.querySelector('.swipeable');
        this.navbarMobile = _navbar.querySelector('.mobile');
        this.navbarMobileMenu = _navbar.querySelector('.menu');
        this.navbarMobileCards = _navbar.querySelectorAll('.mobile-card-view > .menu-panel');

        // Close navbar cards when clicking out of them
        _content.addEventListener('click', e => {
            if(_navbar.querySelector('.card.active'))
                _navbar.querySelector('.card.active').classList.remove('active');
        });

        // Simple Handler for touch on mobile menu
        this.navbarSwipeable.addEventListener('swipe', e => {
            if(e.detail.y < 0 || e.detail.x > 0)
                this.navbarMobileMenu.click();
        });
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
            // Start slider interval
            sliders.autoScroll(elem);

            // Dots
            elem.querySelectorAll('.dots > li').forEach(dot => {
                dot.addEventListener('click', e => {
                    // Index of this element in parent
                    let index = utils.indexOf(dot);
                    let slider = dot.closest('.flex-slider');

                    // Restart slider interval
                    sliders.autoScroll(slider);

                    sliders.moveTo(slider, index);
                })
            });

            // Touch
            elem.addEventListener('swipe', e => {
                if(e.detail.x)
                    sliders.swipe(elem, e.detail.x);

                // Restart slider interval
                sliders.autoScroll(elem);
            });
        });
    },

    autoScroll: slider => {
        let scrollTimer = slider.getAttribute('auto-scroll');

        if(scrollTimer) {
            let interval = slider.getAttribute('interval');
            if(interval)
                clearInterval(interval);

            slider.setAttribute('interval', setInterval(e => {
                sliders.play(slider);
            }, scrollTimer));
        }
    },

    play: slider => {
        let dots = slider.querySelector('.dots');
        let index = utils.indexOf(dots.querySelector('.active'));

        index++;
        if(index == dots.children.length)
            index = 0;

        sliders.moveTo(slider, index);
    },

    swipe: (slider, direction = 1) => {
        let dots = slider.querySelector('.dots');
        let index = utils.indexOf(dots.querySelector('.active'));

        if((index == 0 && direction < 0) || (index == dots.children.length - 1 && direction > 0))
            return;

        index += direction;
        sliders.moveTo(slider, index);
    },

    moveTo: (slider, index) => {
        // Change active Dot
        let dots = slider.querySelector('.dots');
        dots.querySelector('.active').classList.remove('active');
        dots.children[index].classList.add('active');

        // Add the translate
        slider.querySelector('ul').style.setProperty('--page', index);
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


                isotope.querySelector('.empty').style.display = isotope.querySelectorAll('.box.active').length ? 'none' : 'block';
            });
        });
    }
}

window.loading = {
    start: e => _loading.className = 'start',
    end: e => _loading.className = 'end'
}

window.swipeable = {
    init: e => {
        document.querySelectorAll('.swipeable').forEach(elem => {
            let startTouch;
            elem.addEventListener('touchstart', e => { startTouch = e.changedTouches[0] }, {passive: true});
            elem.addEventListener('touchend', e => {
                let [dx, dy] = [
                    e.changedTouches[0].clientX - startTouch.clientX,
                    e.changedTouches[0].clientY - startTouch.clientY
                ];

                dx = Math.abs(dx) > 120 ? (dx > 0 ? -1 : 1) : 0;
                dy = Math.abs(dy) > 120 ? (dy > 0 ? -1 : 1) : 0;

                if(dx + dy)
                    elem.dispatchEvent(new CustomEvent('swipe', {'detail': {
                        'x': dx,
                        'y': dy
                    }}));
            }, {passive: true});

            // Touchable
            elem.querySelectorAll('.touchable').forEach(touchable => {
                let startDrag;
                let range = {
                    min: {
                        x: touchable.getAttribute("min-x"),
                        y: touchable.getAttribute("min-y")
                    },
                    max: {
                        x: touchable.getAttribute("max-x"),
                        y: touchable.getAttribute("max-y")
                    },
                }

                touchable.addEventListener('touchstart', e => {
                    touchable.style.setProperty('transition', 'initial');
                    startDrag = e.changedTouches[0];
                }, false);

                touchable.addEventListener('touchmove', e => {
                    let [dx, dy] = [
                        Math.round(e.changedTouches[0].clientX - startDrag.clientX),
                        Math.round(e.changedTouches[0].clientY - startDrag.clientY)
                    ];
                    
                    if(Math.abs(dx) > 32 || Math.abs(dy) > 32)
                        e.preventDefault();

                    // Normalize values
                    if(range.min.x && dx < range.min.x) dx = range.min.x;
                    if(range.max.x && dx > range.max.x) dx = range.max.x;
                    if(range.min.y && dy < range.min.y) dy = range.min.y;
                    if(range.max.y && dy > range.max.y) dy = range.max.y;

                    touchable.style.setProperty('--x', dx + 'px');
                    touchable.style.setProperty('--y', dy + 'px');
                }, false);

                touchable.addEventListener('touchend', e => {
                    touchable.style.removeProperty('transition');
                    touchable.style.setProperty('--x', '0px');
                    touchable.style.setProperty('--y', '0px');
                }, false);
            });
        });
    }
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

        // Swipeable
        swipeable.init();

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
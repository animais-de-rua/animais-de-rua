// Load Modules
import * as utils from './utils.js';

const _loading = document.getElementById('loading'),
    _content = document.getElementById('content'),
    _navbar = document.getElementById('navbar'),
    _forms = document.getElementById('forms'),
    _fetchOptions = {
        credentials: 'same-origin',
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    };

if('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js', { scope: '/' });
}

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
    },

    initLinks: e => {
        // AJAX Links
        queryAll('a.link').forEach(link => {
            link.classList.remove('link');
            link.addEventListener('click', e => {
                let urlPath = e.target.closest('a').href + "?ajax";

                window.scrollTo(0, 0);
                loading.start();
                // _content.classList.remove('anim');

                e.preventDefault();

                // Always refresh cache
                if(navigator.onLine) {
                    fetch(urlPath + "_updated", _fetchOptions).
                        then(responseUpdated => {
                            // Update view
                            processResponse(responseUpdated.clone(), false);

                            // Update cache
                            caches && caches.open('adr').then(cache => {
                                cache.put(urlPath, responseUpdated);
                            });
                        }).
                        catch(e => {
                            window.location = urlPath.replace('?ajax', '');
                        });
                }

                // First content from cache
                caches && caches.open('adr').then(cache => {
                    cache.match(urlPath).then(response => {
                        if(response) {
                            processResponse(response);
                        }
                    });
                });

                function processResponse(response, updateRoute = true) {
                    // Closes mobile menu
                    navbar.close();

                    response.text().then(html => {
                        _content.innerHTML = html;
                        app.init();

                        loading.end();
                        // _content.classList.add('anim');

                        if(updateRoute) {
                            router.push(html, urlPath.replace('?ajax', ''));
                        }

                        let elem = query('[page-title]')
                        document.title = elem ? elem.getAttribute('page-title') : window.Laravel.title;
                    });
                }
            })
        });

        // Stop propagation links
        queryAll('.stopPropagation').forEach(e => {
            e.classList.remove('stopPropagation');
            e.addEventListener('click', e => e.stopPropagation());
        });
    }
}

window.navbar = {
    init: e => {
        // Close navbar cards when clicking out of them
        _content.addEventListener('click', e => {
            if(_navbar.query('.card.active'))
                _navbar.query('.card.active').classList.remove('active');
        });

        // Simple Handler for touch on mobile menu
        _navbar.query('.swipeable').addEventListener('swipe', e => {
            if(e.detail.y < 0 || e.detail.x > 0)
                _navbar.query('.menu').click();
        });
    },

    close: e => {
        if(_navbar.query('.mobile').classList.contains('active')) {
            _navbar.query('.menu').click();
            // In case second menu is oppened
            if(_navbar.query('.mobile').classList.contains('active'))
                setTimeout(e => _navbar.query('.menu').click(), 250);
        }
    },

    onMobileMenuClick: e => {
        if(_navbar.classList.contains('card-view'))
            return _navbar.classList.remove('card-view');

        e.classList.toggle('active');
        _navbar.query('.mobile').classList.toggle('active');
        document.body.classList.toggle('overflow');
    },

    onMobileCardClick: i => {
        _navbar.classList.toggle('card-view');
        let cards = _navbar.queryAll('.mobile-card-view > .menu-panel');

        cards.forEach(card => card.hide());
        cards[i].show();
    },

    onCardClick: (e, i) => {
        if(e.classList.contains('active'))
            return e.classList.remove('active');

        _navbar.queryAll('.cards > .card').forEach(card => card.classList.remove('active'));
        e.classList.add('active');
    }
}

window.sliders = {
    boot: e => {
        // Window resize
        window.addEventListener('resize', function() {
            queryAll('.flex-slider').forEach(elem => sliders.resizePlayer(elem));
        }, true);
    },

    init: e => {
        queryAll('.flex-slider').forEach(elem => {
            // Start slider interval
            sliders.autoScroll(elem);

            // Resize the player
            sliders.resizePlayer(elem);

            // Dots
            elem.queryAll('.dots > li').forEach(dot => {
                dot.addEventListener('click', e => {
                    // Index of the dot element in parent
                    let index = dot.index();
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

            slider.setAttribute('interval', setInterval(e => sliders.play(slider), scrollTimer));
        }
    },

    play: slider => {
        let dots = slider.query('.dots');
        let index = dots.query('.active').index();

        index++;
        if(index == dots.children.length)
            index = 0;

        sliders.moveTo(slider, index);
    },

    swipe: (slider, direction = 1) => {
        let dots = slider.query('.dots');
        let index = dots.query('.active').index();

        if((index == 0 && direction < 0) || (index == dots.children.length - 1 && direction > 0))
            return;

        index += direction;
        sliders.moveTo(slider, index);
    },

    moveTo: (slider, index) => {
        // Change active Dot
        let dots = slider.query('.dots');
        dots.query('.active').classList.remove('active');
        dots.children[index].classList.add('active');

        // Add the translate
        slider.query('ul').style.setProperty('--page', index);

        // Change player size
        sliders.resizePlayer(slider);
    },

    resizePlayer: slider => {
        let activeDot = slider.query('.dots').query('.active');
        if(activeDot) {
            let index = activeDot ? activeDot.index() : 0;
            let ul = slider.query('ul');
            ul.style.height = ul.children[index].clientHeight + "px";
        }
    }
}

window.accordions = {
    init: e => {
        queryAll('.accordion > h1').forEach(elem => {
            elem.addEventListener('click', e => e.target.parentElement.classList.toggle('open'));
        });
    }
}

window.isotope = {
    init: e => {
        queryAll('.isotope select').forEach(elem => {
            elem.addEventListener('change', e => {
                let filters = [];
                let isotope = e.target.closest('.isotope');

                isotope.queryAll('select').forEach(select => {
                    if(select.value)
                        filters.push('[' + select.getAttribute('type') + '~="' + select.value + '"]');
                });

                isotope.queryAll('.box').forEach(box => box.classList.remove('active'));
                isotope.queryAll('.box' + filters.join('')).forEach(box => box.classList.add('active'));

                let empty = isotope.query('.empty');
                if(empty) empty.display(!isotope.queryAll('.box.active').length);
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
        queryAll('.swipeable').forEach(elem => {
            let startTouch;
            elem.addEventListener('touchstart', e => startTouch = e.changedTouches[0], {passive: true});
            elem.addEventListener('touchend', e => {
                let [dx, dy] = [
                    e.changedTouches[0].clientX - startTouch.clientX,
                    e.changedTouches[0].clientY - startTouch.clientY
                ];

                dx = Math.abs(dx) > 120 ? (dx > 0 ? -1 : 1) : 0;
                dy = Math.abs(dy) > 120 ? (dy > 0 ? -1 : 1) : 0;

                if(dx | dy)
                    elem.dispatchEvent(new CustomEvent('swipe', {'detail': {
                        'x': dx,
                        'y': dy
                    }}));
            }, {passive: true});

            // Touchable
            elem.queryAll('.touchable').forEach(touchable => {
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
                }, {passive: true});

                touchable.addEventListener('touchmove', e => {
                    let [dx, dy] = [
                        Math.round(e.changedTouches[0].clientX - startDrag.clientX),
                        Math.round(e.changedTouches[0].clientY - startDrag.clientY)
                    ];
                    
                    /*if(Math.abs(dx) > 32 || Math.abs(dy) > 32)
                        e.preventDefault();*/

                    // Normalize values
                    if(range.min.x && dx < range.min.x) dx = range.min.x;
                    if(range.max.x && dx > range.max.x) dx = range.max.x;
                    if(range.min.y && dy < range.min.y) dy = range.min.y;
                    if(range.max.y && dy > range.max.y) dy = range.max.y;

                    touchable.style.setProperty('--x', dx + 'px');
                    touchable.style.setProperty('--y', dy + 'px');
                }, {passive: true});

                touchable.addEventListener('touchend', e => {
                    touchable.style.removeProperty('transition');
                    touchable.style.setProperty('--x', '0px');
                    touchable.style.setProperty('--y', '0px');
                }, {passive: true});
            });
        });
    }
}

window.app = {
    boot: e => {
        // Sliders
        sliders.boot();

        window.onresize = e => {
            if(window.innerWidth <= 768) 
                _navbar.query('.mobile').style.height = `${window.innerHeight}px`;
        };
        window.onresize();

        // Network events
        if(!'onLine' in navigator) navigator.onLine = true;
        let updateOnlineStatus = e => navigator.onLine ?
            document.body.classList.remove('offline'):
            document.body.classList.add('offline');
        window.addEventListener('online',  updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        updateOnlineStatus();
    },

    token: e => {
        return fetch('/api/token').then(response => response.json().then(result => result.token));
    },

    init: e => {
        // Load initial page scripts
        let script = document.getElementById('onLoad');
        if(script) eval(script.innerHTML);

        // AJAX Links controller
        router.initLinks();

        // Swipeable
        swipeable.init();

        // Sliders
        sliders.init();

        // Accordion
        accordions.init();

        // Isotope
        isotope.init();

        // Ajax forms
        queryAll('form.ajax').forEach(form => {
            form.classList.remove('ajax');

            form.onsubmit = e => {
                app.token().then(token => {
                    // Update token
                    form.query('[name="_token"]').value = token;

                    let options = {
                        credentials: _fetchOptions.credentials,
                        headers: _fetchOptions.headers,
                        method: 'POST',
                        body: new FormData(form),
                    };

                    let resultsDom = form.query('.result');
                    resultsDom.innerHTML = "";
                    form.classList.remove('error');

                    loading.start();
                    fetch(form.action, options).then(response => {
                        response.json().then(result => {
                            if(result.errors) {
                                form.classList.add('error');
                                for(let error in result.errors)
                                    resultsDom.innerHTML += result.errors[error] + "<br />";
                            } else {
                                resultsDom.innerHTML = result.message;
                                form.reset();
                            }
                        });
                    }).catch(e => {
                        
                    }).finally(e => {
                        loading.end();
                    });
                });

                return false;
            }
        });
    },

    onModalitiesClick: e => {
        let index = e.parentElement.index();
        let form =  query('.modalities form');
        form.query('select > option:nth-child(' + (index + 1) + ')').selected = true;
        form.submit();
    },

    onAnimalsCategorySelect: e => {
        let isotope = e.closest('.isotope');
        let option = e.getAttribute('option');

        // Toggle active buttons
        e.sibling('.active').classList.remove('active');
        e.classList.add('active');

        // Toggle selects
        isotope.queryAll('select.toggle').forEach(e => e.classList.add('hide'));
        isotope.query('select.' + option).classList.remove('hide');

        app.searchAnimals();
    },

    searchAnimals: e => {
        let isotope = query('.isotope');

        loading.start();
        isotope.query('.results-loading').show();
        isotope.queryAll('.box').forEach(e => isotope.removeChild(e));
        isotope.query('.results-empty').hide();

        let option = isotope.query('.options a.active').getAttribute('option');
        let district = isotope.query('select.toggle:not(.hide)').value;
        let specie = isotope.query('select.specie').value;

        fetch(`/api/animals/${option}/${district}/${specie}/`, _fetchOptions).then(response => {
            response.json().then(data => {
                isotope.query('.results-loading').hide();

                let template = document.getElementById('animal-box-template');
                data.forEach(elem => {
                    let box = template.content.cloneNode(true);
                    let date = new Date(elem.created_at);

                    if(elem.images)
                        box.query('.image img').src = elem.images[0];
                    box.query('.name').innerText = elem.name;
                    box.query('.location').innerText = elem.county + ", " + elem.district;
                    box.query('.date').innerText = translations.month[date.getMonth()] + " " + date.getFullYear();

                    box.query('.box').setAttribute('animal', elem.id);
                    box.query('.box').setAttribute('option', option);

                    box.query('a').setAttribute('href', `/animals/${option}/${elem.id}`);

                    isotope.appendChild(box);
                });

                if(!data.length) {
                    isotope.query('.results-empty').show();
                }

                router.initLinks();

                loading.end();
            })
        }).catch(e => { });
    },
}

window.modal = {
    open: e => {
        _forms.query('.header').show();
        _forms.query('.godfather').hide();

        _forms.classList.add('open');
        _forms.classList.remove('sending', 'success');
        _forms.query(`.options > option[value="${e}"]`).selected = true;
        modal.onCategorySelect();
        return false;
    },

    openGodfather: e => {
        _forms.query('.header').hide();
        _forms.query('.godfather').show();
        _forms.queryAll('.form').forEach(e => e.hide());
        _forms.query('.form.godfather').show();
        _forms.query('.godfather h1').innerHTML = _forms.query('.godfather [name="process_name"]').value = query('#animals-view h1').innerText;
        _forms.query('.godfather [name="process_id"]').value = window.location.href.match(/\d+$/)[0];

        _forms.classList.add('open');
        return false;
    },

    clearGroup: e => {
        e.parentElement.queryAll('[type="radio"]').forEach(e => e.checked = false);
    },

    close: e => {
        _forms.classList.remove('open');
    },

    onCategorySelect: e => {
        let select = _forms.query('.options');
        let className = select.children[select.selectedIndex].value;

        _forms.queryAll('.form').forEach(e => e.hide());
        
        let form = _forms.query(`.form.${className}`);
        form.show();

        modal.moveAddressSelects(form);
    },

    moveAddressSelects: form => {
        let selects = queryAll('.address-selects > select');
        let destination = form.query('.address-selects');

        if(destination) {
            selects.forEach(e => destination.appendChild(e));
        }
    },

    onDistrictSelect: e => {
        modal.checkEmptySelect(e);

        let id = e.children[e.selectedIndex].value;
        let countyOptions = e.nextElementSibling.queryAll('option');

        countyOptions.forEach(e => e.display(e.getAttribute('parent') == id));
        countyOptions[0].selected = true;
    },

    checkEmptySelect: e => {
        let value = e.children[e.selectedIndex].value;
        value ? e.classList.remove('empty') : e.classList.remove('empty');
    },

    submit: form => {
        loading.start();
        _forms.classList.add("sending");
        _forms.query('.errors').hide();
        _forms.queryAll('input.error').forEach(e => e.classList.remove('error'));

        // Update token
        app.token().then(token => {
            form.query('[name="_token"]').value = token;

            let options = Object.assign(_fetchOptions, {
                method: 'POST',
                body: new FormData(form),
            });

            fetch(form.action, options).then(response => {
                response.json().then(result => {

                    if(result.success) {
                        _forms.classList.add("success");
                        _forms.query('.success > p').innerHTML = result.message;
                        form.reset();
                    } else {
                        let errorsDiv = _forms.query('.errors');

                        errorsDiv.show();
                        errorsDiv.innerHTML = "Error";

                        if(result.errors) {
                            let errorsList = "";
                            for (var error in result.errors) {
                                errorsList += "<p>" + result.errors[error][0] + "</p>";

                                let input = form.query(`input[name="${error.replace(/\.\d/, '[]')}"]`);
                                if(input) input.classList.add('error');
                            }

                            errorsDiv.innerHTML = errorsList;
                        } else {
                            // Unknown error
                            if(result.message)
                                errorsDiv.innerHTML = result.message;
                        }
                    }
                });
            }).catch(e => {

            }).finally(e => {
                loading.end();
                _forms.classList.remove("sending");
            });

        });

        return false;
    }
}

document.addEventListener('DOMContentLoaded', e => {
    app.boot();

    app.init();
    router.init();
    navbar.init();

    // Website info log
    console.log('%canimais de rua\n%cThis is a free and open source project by %cpromatik.pt\n%cIf you find any bugs or security issues please report at %cgithub.com/animais-de-rua/intranet', 'color:#e03322; font-weight: bold', 'color:#444', 'color:#050055;font-weight:700', 'color:#777;font-style:italic', 'color:#050055;font-weight:700;font-style:italic');
});

window.utils = utils;
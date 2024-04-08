// Load Modules
import { onDomReady } from 'cantil';
import * as utils from './utils';

const loadingDom = document.getElementById('loading');
const contentDom = document.getElementById('content');
const navbarDom = document.getElementById('navbar');
const formsDom = document.getElementById('forms');
const fetchOptions = {
  credentials: 'same-origin',
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
};

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js', { scope: '/' });
}

window.router = {
  init: () => {
    window.history.pushState({ html: contentDom.innerHTML }, '', window.location.pathname);

    window.onpopstate = e => {
      if (e.state) {
        contentDom.innerHTML = e.state.html;
        app.init();
      }
    };
  },

  push: (content, urlPath) => {
    window.history.pushState({ html: content }, '', urlPath);
  },

  initLinks: () => {
    function processResponse(response, urlPath) {
      // Closes mobile menu
      navbar.close();

      response.text().then(html => {
        if (contentDom.innerHTML !== html) {
          contentDom.innerHTML = html;
          app.init();
        }

        loading.end();

        router.push(html, urlPath.replace('?ajax', ''));

        const elem = query('[page-title]');
        document.title = elem ? elem.getAttribute('page-title') : window.Laravel.title;
      });
    }

    // AJAX Links
    queryAll('a.link').forEach(link => {
      link.classList.remove('link');
      link.addEventListener('click', e => {
        const urlPathClean = e.target.closest('a').href;
        const urlPath = `${urlPathClean}?ajax`;

        // Pixel track
        app.track('PageView', {
          path: window.location.pathname,
        });

        window.scrollTo(0, 0);
        loading.start();
        // contentDom.classList.remove('anim');

        e.preventDefault();

        // Always refresh cache
        if (navigator.onLine) {
          fetch(`${urlPath}_updated`, fetchOptions)
            .then(responseUpdated => {
              // Update view
              processResponse(responseUpdated.clone(), urlPathClean);

              // Update cache
              if (caches) {
                caches.open('adr').then(cache => {
                  cache.put(urlPath, responseUpdated);
                });
              }
            })
            .catch(() => {
              window.location = urlPathClean;
            });
        }

        // First content from cache
        if (caches) {
          caches.open('adr').then(cache => {
            cache.match(urlPath).then(response => {
              if (response) {
                processResponse(response, urlPathClean);
              }
            });
          });
        }
      });
    });

    // Stop propagation links
    queryAll('.stopPropagation').forEach(elem => {
      elem.classList.remove('stopPropagation');
      elem.addEventListener('click', e => e.stopPropagation());
    });
  },
};

window.navbar = {
  init: () => {
    // Close navbar cards when clicking out of them
    contentDom.addEventListener('click', () => {
      if (navbarDom.query('.card.active')) { navbarDom.query('.card.active').classList.remove('active'); }
    });

    // Simple Handler for touch on mobile menu
    navbarDom.query('.swipeable').addEventListener('swipe', e => {
      if (e.detail.y < 0 || e.detail.x > 0) { navbarDom.query('.menu').click(); }
    });
  },

  close: () => {
    if (navbarDom.query('.mobile').classList.contains('active')) {
      navbarDom.query('.menu').click();
      // In case second menu is oppened
      if (navbarDom.query('.mobile').classList.contains('active')) { setTimeout(() => navbarDom.query('.menu').click(), 250); }
    }
  },

  onMobileMenuClick: e => {
    if (navbarDom.classList.contains('card-view')) { return navbarDom.classList.remove('card-view'); }

    e.classList.toggle('active');
    navbarDom.query('.mobile').classList.toggle('active');
    document.body.classList.toggle('overflow');
  },

  onMobileCardClick: i => {
    navbarDom.classList.toggle('card-view');
    const cards = navbarDom.queryAll('.mobile-card-view > .menu-panel');

    cards.forEach(card => card.hide());
    cards[i].show();
  },

  onCardClick: (e, i) => {
    if (e.classList.contains('active')) {
      e.classList.remove('active');
    } else {
      navbarDom.queryAll('.cards > .card').forEach(card => card.classList.remove('active'));
      e.classList.add('active');

      // Pixel track
      app.track('ViewContent', {
        card: i ? 'donate' : 'friend_card',
      });
    }
  },
};

window.sliders = {
  boot: () => {
    // Window resize
    window.addEventListener('resize', () => {
      queryAll('.flex-slider').forEach(elem => sliders.resizePlayer(elem));
    }, true);
  },

  init: () => {
    queryAll('.flex-slider').forEach(elem => {
      // Start slider interval
      sliders.autoScroll(elem);

      // Resize the player
      sliders.resizePlayer(elem);

      // Dots
      elem.queryAll('.dots > li').forEach(dot => {
        dot.addEventListener('click', () => {
          // Index of the dot element in parent
          const index = dot.index();
          const slider = dot.closest('.flex-slider');

          // Restart slider interval
          sliders.autoScroll(slider);

          sliders.moveTo(slider, index);
        });
      });

      // Touch
      elem.addEventListener('swipe', e => {
        if (e.detail.x) { sliders.swipe(elem, e.detail.x); }

        // Restart slider interval
        sliders.autoScroll(elem);
      });
    });
  },

  autoScroll: slider => {
    const scrollTimer = slider.getAttribute('auto-scroll');

    if (scrollTimer) {
      const interval = slider.getAttribute('interval');
      if (interval) { clearInterval(interval); }

      slider.setAttribute('interval', setInterval(() => sliders.play(slider), scrollTimer));
    }
  },

  play: slider => {
    const dots = slider.query('.dots');
    let index = dots.query('.active').index();

    index += 1;
    if (index === dots.children.length) { index = 0; }

    sliders.moveTo(slider, index);
  },

  swipe: (slider, direction = 1) => {
    const dots = slider.query('.dots');
    let index = dots.query('.active').index();

    if ((index === 0 && direction < 0) || (index === dots.children.length - 1 && direction > 0)) {
      return;
    }

    index += direction;
    sliders.moveTo(slider, index);
  },

  moveTo: (slider, index) => {
    // Change active Dot
    const dots = slider.query('.dots');
    dots.query('.active').classList.remove('active');
    dots.children[index].classList.add('active');

    // Add the translate
    slider.query('ul').style.setProperty('--page', index);

    // Change player size
    sliders.resizePlayer(slider);
  },

  resizePlayer: slider => {
    const activeDot = slider.query('.dots').query('.active');
    if (activeDot) {
      const index = activeDot ? activeDot.index() : 0;
      const ul = slider.query('ul');
      ul.style.height = `${ul.children[index].clientHeight}px`;
    }
  },
};

window.accordions = {
  init: () => {
    queryAll('.accordion > h1').forEach(elem => {
      elem.addEventListener('click', e => e.target.parentElement.classList.toggle('open'));
    });
  },
};

window.isotope = {
  init: () => {
    queryAll('.isotope select').forEach(elem => {
      elem.addEventListener('change', e => {
        const filters = [];
        const isotope = e.target.closest('.isotope');

        isotope.queryAll('select').forEach(select => {
          if (select.value) { filters.push(`[${select.getAttribute('type')}~="${select.value}"]`); }
        });

        isotope.queryAll('.box').forEach(box => box.classList.remove('active'));
        isotope.queryAll(`.box${filters.join('')}`).forEach(box => box.classList.add('active'));

        const empty = isotope.query('.empty');
        if (empty) empty.display(!isotope.queryAll('.box.active').length);
      });
    });
  },
};

window.loading = {
  start: () => loadingDom.className = 'start',
  end: () => loadingDom.className = 'end',
};

window.swipeable = {
  init: () => {
    queryAll('.swipeable').forEach(elem => {
      let startTouch;
      elem.addEventListener('touchstart', e => [startTouch] = e.changedTouches, { passive: true });
      elem.addEventListener('touchend', e => {
        let [dx, dy] = [
          e.changedTouches[0].clientX - startTouch.clientX,
          e.changedTouches[0].clientY - startTouch.clientY,
        ];

        dx = Math.abs(dx) > 120 ? (dx > 0 ? -1 : 1) : 0;
        dy = Math.abs(dy) > 120 ? (dy > 0 ? -1 : 1) : 0;

        if (dx | dy) {
          elem.dispatchEvent(new CustomEvent('swipe', {
            detail: {
              x: dx,
              y: dy,
            },
          }));
        }
      }, { passive: true });

      // Touchable
      elem.queryAll('.touchable').forEach(touchable => {
        let startDrag;
        const range = {
          min: {
            x: touchable.getAttribute('min-x'),
            y: touchable.getAttribute('min-y'),
          },
          max: {
            x: touchable.getAttribute('max-x'),
            y: touchable.getAttribute('max-y'),
          },
        };

        touchable.addEventListener('touchstart', e => {
          touchable.style.setProperty('transition', 'initial');
          [startDrag] = e.changedTouches;
        }, { passive: true });

        touchable.addEventListener('touchmove', e => {
          let [dx, dy] = [
            Math.round(e.changedTouches[0].clientX - startDrag.clientX),
            Math.round(e.changedTouches[0].clientY - startDrag.clientY),
          ];

          /* if(Math.abs(dx) > 32 || Math.abs(dy) > 32)
                        e.preventDefault(); */

          // Normalize values
          if (range.min.x && dx < range.min.x) dx = range.min.x;
          if (range.max.x && dx > range.max.x) dx = range.max.x;
          if (range.min.y && dy < range.min.y) dy = range.min.y;
          if (range.max.y && dy > range.max.y) dy = range.max.y;

          touchable.style.setProperty('--x', `${dx}px`);
          touchable.style.setProperty('--y', `${dy}px`);
        }, { passive: true });

        touchable.addEventListener('touchend', () => {
          touchable.style.removeProperty('transition');
          touchable.style.setProperty('--x', '0px');
          touchable.style.setProperty('--y', '0px');
        }, { passive: true });
      });
    });
  },
};

window.app = {
  boot: () => {
    // Sliders
    sliders.boot();

    window.onresize = () => {
      if (window.innerWidth <= 768) { navbarDom.query('.mobile').style.height = `${window.innerHeight}px`; }
    };
    window.onresize();

    // Network events
    if (!('onLine' in navigator)) navigator.onLine = true;
    const updateOnlineStatus = () => (navigator.onLine
      ? document.body.classList.remove('offline')
      : document.body.classList.add('offline'));
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus();
  },

  token: () => fetch('/api/token').then(response => response.json().then(result => result.token)),

  init: () => {
    // Load initial page scripts
    const script = document.getElementById('onLoad');
    // eslint-disable-next-line no-eval
    if (script) eval(script.innerHTML);

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

    // Selectable
    queryAll('.selectable').forEach(e => e.onclick = () => {
      const selection = window.getSelection();
      const range = document.createRange();
      range.selectNodeContents(e.query('p'));
      selection.removeAllRanges();
      selection.addRange(range);
    });

    // Ajax forms
    queryAll('form.ajax').forEach(form => {
      form.classList.remove('ajax');

      form.onsubmit = () => {
        app.token().then(token => {
          // Update token
          form.query('[name="_token"]').value = token;

          const options = {
            ...fetchOptions,
            method: 'POST',
            body: new FormData(form),
          };

          const resultsDom = form.query('.result');
          resultsDom.innerHTML = '';
          form.classList.remove('error');

          loading.start();
          fetch(form.action, options).then(response => {
            response.json().then(result => {
              if (result.errors) {
                form.classList.add('error');
                Object.entries(result.errors).forEach(([, value]) => {
                  resultsDom.innerHTML += `${value}<br />`;
                });
              } else {
                resultsDom.innerHTML = result.message;
                form.reset();
              }
            });
          }).catch(() => {

          }).finally(() => {
            loading.end();
          });

          // Pixel track
          app.track('ViewContent', {
            form: form.action,
          });
        });

        return false;
      };
    });

    // Partners randomize
    const partnersContainer = query('.sponsors .grid');
    if (partnersContainer) {
      const partnersList = partnersContainer.queryAll('a');

      partnersContainer.innerHTML = '';
      partnersList.map(value => ({ value, sort: Math.random() }))
        .sort((a, b) => a.sort - b.sort)
        .map(({ value }) => value)
        .forEach(e => {
          partnersContainer.appendChild(e);
          e.classList.add('active');
        });
    }
  },

  onModalitiesClick: e => {
    const index = e.parentElement.index();
    const form = query('.modalities form');
    form.query(`select > option:nth-child(${index + 1})`).selected = true;
    form.submit();
  },

  onAnimalsCategorySelect: e => {
    const isotope = e.closest('.isotope');
    const option = e.getAttribute('option');

    // Toggle active buttons
    e.sibling('.active').classList.remove('active');
    e.classList.add('active');

    // Toggle selects
    isotope.queryAll('select.toggle').forEach(elem => elem.classList.add('hide'));
    isotope.query(`select.${option}`).classList.remove('hide');

    app.searchAnimals();

    // Pixel track
    app.track('ViewContent', {
      form: option,
    });
  },

  searchAnimals: () => {
    const isotope = query('.isotope');

    loading.start();
    isotope.query('.results-loading').show();
    isotope.queryAll('.box').forEach(e => isotope.removeChild(e));
    isotope.query('.results-empty').hide();

    const option = isotope.query('.options a.active').getAttribute('option');
    const district = isotope.query('select.toggle:not(.hide)').value;
    const specie = isotope.query('select.specie').value;

    fetch(`/api/animals/${option}/${district}/${specie}/`, fetchOptions).then(response => {
      response.json().then(data => {
        isotope.query('.results-loading').hide();

        const template = document.getElementById('animal-box-template');
        data.forEach(elem => {
          const box = template.content.cloneNode(true);
          const date = new Date(elem.created_at);

          if (elem.images) { box.query('.image img').src = elem.thumb; }
          box.query('.name').innerText = elem.name;
          box.query('.location').innerText = `${elem.county}, ${elem.district}`;
          box.query('.date').innerText = `${translations.month[date.getMonth()]} ${date.getFullYear()}`;

          box.query('.box').setAttribute('animal', elem.id);
          box.query('.box').setAttribute('option', option);

          box.query('a').setAttribute('href', `/animals/${option}/${elem.id}`);

          isotope.appendChild(box);
        });

        if (!data.length) {
          isotope.query('.results-empty').show();
        }

        router.initLinks();

        loading.end();
      });
    }).catch(() => { });
  },

  track: (event, data) => {
    fbq('track', event, data);
  },
};

window.modal = {
  open: e => {
    formsDom.query('.header').show();
    formsDom.query('.godfather').hide();

    formsDom.classList.add('open');
    formsDom.classList.remove('sending', 'success');
    formsDom.query(`.options > option[value="${e}"]`).selected = true;
    modal.onCategorySelect();

    // Pixel track
    app.track('ViewContent', {
      modal: e,
    });

    return false;
  },

  openGodfather: () => {
    formsDom.query('.header').hide();
    formsDom.query('.godfather').show();
    formsDom.queryAll('.form').forEach(e => e.hide());
    formsDom.query('.form.godfather').show();
    formsDom.query('.godfather h1').innerHTML = query('#animals-view h1').innerText;
    formsDom.query('.godfather [name="process_name"]').value = query('#animals-view h1').innerText;
    // eslint-disable-next-line prefer-destructuring
    formsDom.query('.godfather [name="process_id"]').value = (window.location.href.match(/\d+$/) || [0])[0];

    formsDom.classList.add('open');

    // Pixel track
    app.track('ViewContent', {
      modal: 'godfather',
    });

    return false;
  },

  clearGroup: e => {
    e.parentElement.queryAll('[type="radio"]').forEach(elem => elem.checked = false);
  },

  close: () => {
    formsDom.classList.remove('open');
  },

  onCategorySelect: e => {
    const select = formsDom.query('.options');
    const className = select.children[select.selectedIndex].value;

    formsDom.queryAll('.form').forEach(elem => elem.hide());

    const form = formsDom.query(`.form.${className}`);
    form.show();

    modal.moveAddressSelects(form);

    // Pixel track
    app.track('ViewContent', {
      modal: e,
    });
  },

  moveAddressSelects: form => {
    const selects = queryAll('.address-selects > select');
    const destination = form.query('.address-selects');

    if (destination) {
      selects.forEach(e => destination.appendChild(e));
    }
  },

  onDistrictSelect: e => {
    modal.checkEmptySelect(e);

    const id = e.children[e.selectedIndex].value;
    const countyOptions = e.nextElementSibling.queryAll('option');
    countyOptions.forEach(option => option.display(option.getAttribute('parent') === id));
    countyOptions[0].selected = true;
  },

  checkEmptySelect: e => {
    e.classList.remove('empty');
  },

  openPetsittingForm: e => {
    formsDom.query('.header').hide();
    formsDom.queryAll('.form').forEach(e => e.hide());
    formsDom.query('.form.petsitting').show();
    formsDom.classList.add('open');

    // Pixel track
    app.track('ViewContent', {
      modal: 'petsitting',
    });

    return false;
  },

  submit: form => {
    loading.start();
    formsDom.classList.add('sending');
    formsDom.query('.errors').hide();
    formsDom.queryAll('input.error').forEach(e => e.classList.remove('error'));

    // Update token
    app.token().then(token => {
      form.query('[name="_token"]').value = token;

      const options = Object.assign(fetchOptions, {
        method: 'POST',
        body: new FormData(form),
      });

      fetch(form.action, options).then(response => {
        response.json().then(result => {
          if (result.success) {
            formsDom.classList.add('success');
            formsDom.query('.success > p').innerHTML = result.message;
            form.reset();
          } else {
            const isPetsittingForm = new URL(form.action).pathname === '/form/petsitting';
            const errorsDiv = formsDom.query(isPetsittingForm ? '.petsitting-error' : '.errors');

            errorsDiv.show();
            errorsDiv.innerHTML = 'Error';

            if (result.errors) {
              let errorsList = '';
              Object.entries(result.errors).forEach(([key, value]) => {
                errorsList += `<p>${value}</p>`;

                const input = form.query(`input[name="${key.replace(/\.\d/, '[]')}"]`) ?? form.query(`input[name="${key}[]"]`);
                if (input && isPetsittingForm) {
                  if (input.type !== 'checkbox' && input.type !== 'radio') {
                    input.classList.add('error');
                    
                    let errorMessage = input.nextElementSibling;
                    if (!errorMessage) {
                      errorMessage = document.createElement('span');
                      errorMessage.classList.add('error-message');
                      errorMessage.innerHTML = value;
                      input.insertAdjacentElement('afterend', errorMessage);
                    } else {
                      errorMessage.innerHTML = value;
                    }
                  } else {
                    const container = input.closest('.column > div');

                    if (container) {
                      container.classList.add('error');

                      let errorMessage = container.nextElementSibling;
                      if (!errorMessage) {
                        errorMessage = document.createElement('span');
                        errorMessage.classList.add('error-message');
                        errorMessage.innerHTML = value;
                        container.insertAdjacentElement('afterend', errorMessage);
                      } else {
                        errorMessage.innerHTML = value;
                      }
                    }
                  }
                } else if (input) {
                  input.classList.add('error');
                }
              });

              if (isPetsittingForm) {
                errorsDiv.classList.add('active');
                errorsDiv.innerHTML = result.message;
              } else {
                errorsDiv.innerHTML = errorsList;
              }
            } else if (result.message) {
              // Unknown error
              errorsDiv.innerHTML = result.message;
            }
          }
        });
      }).catch(() => {

      }).finally(() => {
        loading.end();
        formsDom.classList.remove('sending');
      });
    });

    return false;
  },
};

onDomReady().then(() => {
  app.boot();

  app.init();
  router.init();
  navbar.init();

  // Website info log
  console.log('%canimais de rua\n%cThis is a free and open source project by %cpromatik.pt\n%cIf you find any bugs or security issues please report at %cgithub.com/animais-de-rua/intranet', 'color:#e03322; font-weight: bold', 'color:#444', 'color:#050055;font-weight:700', 'color:#777;font-style:italic', 'color:#050055;font-weight:700;font-style:italic');
});

window.utils = utils;

// Load Modules
import * as utils from './utils.js';

const loader = document.querySelector('#loading'),
    content = document.getElementById('content'),
    navbar = document.querySelector('.navbar'),
    navbarCards = navbar.querySelectorAll('.cards > .card'),
    navbarMobile = navbar.querySelector('.mobile'),
    navbarMobileMenu = navbar.querySelector('.menu'),
    navbarMobileCards = navbar.querySelectorAll('.mobile-card-view > .menu-panel');

// Main Code
document.addEventListener('DOMContentLoaded', e => {
    loadLinks();

    // Close cards when clicking out of them
    content.addEventListener('click', e => {
        if(navbar.querySelector('.card.active'))
            navbar.querySelector('.card.active').classList.remove('active');
    });

    // Stop propagation
    document.querySelectorAll('.stopPropagation').forEach(e => e.addEventListener('click', e => e.stopPropagation()));

    // Router control
    window.history.pushState({'html': content.innerHTML}, '', location.pathname);
    window.onpopstate = e => {
        if(e.state)
            content.innerHTML = e.state.html;
    }

    // Simple Handler for touch on mobile menu
    let startTouch;
    navbarMobile.addEventListener("touchstart", e => { startTouch = e.changedTouches[0] }, {passive: true});
    navbarMobile.addEventListener("touchend", e => {
        let [diffX, diffY] = [
            e.changedTouches[0].clientX - startTouch.clientX,
            e.changedTouches[0].clientY - startTouch.clientY
        ];

        if((navbar.classList.contains("card-view") && diffY > 60) || diffX < -60)
            navbarMobileMenu.click();
    }, {passive: true});

    // Sliders
    document.querySelectorAll('.flex-slider').forEach(elem => {
        sliderStart(elem);

        // Dots
        elem.querySelectorAll('.dots > li').forEach(dot => {
            dot.addEventListener('click', e => {
                // Index of this element in parent
                let index = utils.indexOf(dot);
                let slider = dot.closest('.flex-slider');

                // Restart slider interval
                sliderStart(slider);

                sliderMoveTo(slider, index);
            })
        });
    });
});


window.sliderStart = slider => {
    let autoScroll = slider.getAttribute('auto-scroll');

    if(autoScroll) {
        let interval = slider.getAttribute('interval');
        if(interval)
            clearInterval(interval);

        slider.setAttribute('interval', setInterval(() => {
            sliderMove(slider);
        }, autoScroll));
    }
}

window.sliderMove = slider => {
    let dots = slider.querySelector('.dots');
    let index = utils.indexOf(dots.querySelector('.active'));

    index++;
    if(index == dots.children.length)
        index = 0;

    sliderMoveTo(slider, index);
}

window.sliderMoveTo = (slider, index) => {
    // Change active Dot
    let dots = slider.querySelector('.dots');
    dots.querySelector('.active').classList.remove('active');
    dots.children[index].classList.add('active');

    // Add the translate
    slider.querySelector('ul').style = "transform: translateX(-" + index * 100 + "%);";
}

window.mobileMenu = e => {
    if(navbar.classList.contains('card-view'))
        return navbar.classList.remove('card-view');

    e.classList.toggle('active');
    navbarMobile.classList.toggle('active');
    document.body.classList.toggle('overflow');
}

window.mobileMenuCard = i => {
    navbar.classList.toggle('card-view');

    navbarMobileCards.forEach(card => card.style.display = 'none');
    navbarMobileCards[i].style.display = 'block';
}

window.menuCard = (e, i) => {
    if(e.classList.contains('active'))
        return e.classList.remove('active');

    navbarCards.forEach(card => card.classList.remove('active'));
    e.classList.add('active');
}

// AJAX Links controller
window.loadLinks = () => {
    document.querySelectorAll('a.link').forEach(link => {
        link.addEventListener('click', e => {
            let urlPath = e.target.href;

            loadingStart();
            content.classList.remove('anim');

            e.preventDefault();
            fetch(urlPath, {
                credentials: 'same-origin',
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            }).then(response => {
                // Closes mobile menu
                if(navbarMobile.classList.contains('active')) {
                    navbar.querySelector('.menu').click();
                    // In case second menu is oppened
                    if(navbarMobile.classList.contains('active'))
                        setTimeout(e => navbar.querySelector('.menu').click(), 250);
                }

                response.text().then(html => {
                    content.innerHTML = html;

                    loadingEnd();
                    content.classList.add('anim');

                    window.history.pushState({'html': html}, '', urlPath);
                })
            }).catch(() => {
                window.location = urlPath;
            });
        })
    });
}

window.loadingStart = () => loader.className = 'start';
window.loadingEnd = () => loader.className = 'end';
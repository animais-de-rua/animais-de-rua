// Load Modules
// import * as easing from 'easing-utils';
import { onDomReady, swipeable, observable } from 'cantil';

window.app = {
  init: () => {
    console.log('App initialized');

    // Swipeable
    swipeable.init();

    // Intersection Observer
    observable.init();
  },
};

onDomReady().then(app.init);

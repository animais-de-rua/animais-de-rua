import { onDomReady, queryAll } from 'cantil';
import BoringAvatar from 'boring-avatars-svg';

const app = {
  init: () => {
    // boring avatars
    for (const e of queryAll('.boring-avatars')) {
      e.innerHTML = BoringAvatar({
        variant: e.dataset.variant ?? 'beam',
        colors: e.dataset.colors.split(','),
        size: e.dataset.size ?? 35,
        name: e.dataset.name,
      });
    }
  },
};

window.app = app;
onDomReady().then(app.init);

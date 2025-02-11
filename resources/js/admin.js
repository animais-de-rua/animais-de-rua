import { onDomReady } from 'cantil';
import BoringAvatar from 'boring-avatars-svg';

window.app = {
  init: () => {
    // boring avatars
    queryAll('.boring-avatars')
      .forEach(e => e.innerHTML = BoringAvatar({
        variant: e.dataset.variant ?? 'beam',
        colors: e.dataset.colors.split(','),
        size: e.dataset.size ?? 35,
        name: e.dataset.name,
      }));
  },
};

onDomReady().then(app.init);

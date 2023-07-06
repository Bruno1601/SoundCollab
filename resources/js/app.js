import './bootstrap';

import Alpine from 'alpinejs';

import Plyr from 'plyr';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('iniciar-reproductor', () => {
    const players = Array.from(document.querySelectorAll('audio[id^="player-"]')).map(element => new Plyr(element));
});

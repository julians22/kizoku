import './bootstrap';
import './toast';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import intersect from '@alpinejs/intersect'

Alpine.plugin(intersect);

Livewire.start();

window.addEventListener('scroll', function() {
    const navigation = document.getElementById('navigation');
    if (window.scrollY > 0) {
        navigation.classList.add('scrolled');
    } else {
        navigation.classList.remove('scrolled');
    }
});

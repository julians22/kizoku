import Glide from '@glidejs/glide';

export function initGlide(selector, options) {
    new Glide(selector, options).mount();
}

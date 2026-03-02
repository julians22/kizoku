import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

const TOAST_COLOUR = {
    SUCCESS: '#000000',
    ERROR: '#000000',
    INFO: '#000000',
    WARNING: '#000000'
}

function toaster(text, type){
    let options = {
        text: text,
        duration: 3000,
        close: true,
        gravity: "bottom", // `top` or `bottom`
        position: "left", // `left`, `center` or `right`
        style: {
            background: TOAST_COLOUR[type.toUpperCase()]
        },
        onClick: function(){} // Callback after click
    }

    return Toastify(options).showToast();
}


window.toaster = toaster;

require('./bootstrap');
import { registerEventListeners } from "./EventListeners.js"

window.addEventListener("DOMContentLoaded", function() {
    console.log("Dom loaded")
    registerEventListeners()
}, false);

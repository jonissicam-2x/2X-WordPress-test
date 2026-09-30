import Collapse from 'bootstrap/js/dist/collapse';

document.addEventListener("DOMContentLoaded", () => {
    const menuElement = document.getElementById('navbarNav');
    const togglerButton = document.getElementById('navbarToggle');

    // Initialize the Collapse instance directly using the imported module
    const bsCollapse = new Collapse(menuElement, {
        toggle: false
    });
    
    // Toggle the navbar when clicking the burger icon
    togglerButton.addEventListener('click', () => {
        bsCollapse.toggle();
    });
});
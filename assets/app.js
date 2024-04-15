import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';




// Menu Hamburger
    const menuHamburger = document.querySelector(".menu-hamburger");
    const navLinks = document.querySelector(".container-fluid");

        menuHamburger.addEventListener('click', () => {
            navLinks.classList.toggle('mobile-menu');
        });

// Ajoute un écouteur d'événement au redimensionnement de la fenêtre
    window.addEventListener('resize', () => {

// Vérifie si le menu mobile est affiché et que la largeur de la fenêtre est supérieure à 900 pixels
    if (navLinks.classList.contains('mobile-menu') && window.innerWidth > 900) {
        navLinks.classList.remove('mobile-menu');
    }
});



// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

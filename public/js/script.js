

// Menu Hamburger

// Sélectionnez l'élément du menu hamburger en utilisant un sélecteur plus spécifique (si nécessaire)
const menuHamburger = document.querySelector(".menu-hamburger");
const navLinks = document.querySelector(".nav-links");
const linksModele = document.querySelector(".links-modele");
const linksPhotographe = document.querySelector(".links-photographe");


// // Vérifiez qu'il n'y a pas d'erreurs JavaScript
// console.log("menuHamburger :", menuHamburger); // Vérification de la sélection de l'élément


// Sélectionnez tous les liens à l'intérieur du menu hamburger
const menuLinks = document.querySelectorAll(".nav-links a");

// Ajoutez un gestionnaire d'événements à chaque lien du menu hamburger
menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        // Fermez le menu en retirant la classe 'mobile-menu'
        navLinks.classList.remove('mobile-menu');
        
        // Vous pouvez également cacher les liens spécifiques ici si nécessaire
        if (isModele) {
            linksModele.classList.remove('hidden');
        }
        if (isPhotographe) {
            linksPhotographe.classList.remove('hidden');
        }
    });
});

// Ajoutez un écouteur d'événement au redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    // Vérifiez si le menu mobile est affiché et que la largeur de la fenêtre est supérieure à 900 pixels
    if (navLinks.classList.contains('mobile-menu') && window.innerWidth > 900) {
        navLinks.classList.remove('mobile-menu');
    }
});

menuHamburger.addEventListener('click', () => {
    // Basculez la classe 'mobile-menu' sur le conteneur du menu mobile
    navLinks.classList.toggle('mobile-menu');

    if (navLinks.classList.contains('mobile-menu')) {
        if (isModele) {
            linksModele.classList.add('hidden');
        }
        if (isPhotographe) {
            linksPhotographe.classList.add('hidden');
        }
    } else {
        if (isModele) {
            linksModele.classList.remove('hidden');
        }
        if (isPhotographe) {
            linksPhotographe.classList.remove('hidden');
        }
    }
});
// Scroll to Top Button

// execute la fonction lorsque la fenêtre est scrollée
window.onscroll = function () { scrollFunction() };

// lorsque user scroll down à 30px du haut de la page, montre le bouton
function scrollFunction() { 
    // récupère le bouton
    var mybutton = document.getElementById("scrollToTopButton");

    if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
        mybutton.style.opacity = 1;
        mybutton.style.visibility = "visible";
    } else {
        mybutton.style.opacity = 0;
        mybutton.style.visibility = "hidden";
    }
}

// lorsque user click sur le bouton, scroll to the top
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

const loadFile = function (event) {
    const image = document.getElementById('image');

    if(image) {
        // Afficher l'aperçu de l'image sélectionnée
        image.src = URL.createObjectURL(event.target.files[0]);
    }
};

////////////////////////////////////////////

document.addEventListener("DOMContentLoaded", function() {
    // Vérifier si nous sommes sur la page 'bibliotheque.html.twig'
    if (document.querySelector('.thumbnails')) {
        // Sélectionnez toutes les miniatures
        var thumbnails = document.querySelectorAll('.thumbnail-image');

        // Ajoutez un écouteur d'événement clic à chaque miniature
        thumbnails.forEach(function(thumbnail) {
            thumbnail.addEventListener('click', function() {
                var imageSrc = this.getAttribute('src');

                // Mettez à jour l'élément image en plein écran avec la source de l'image miniature
                document.getElementById('fullscreen-image').setAttribute('src', imageSrc);

                // Affichez l'overlay en plein écran
                document.getElementById('fullscreen-overlay').style.display = 'flex';
            });
        });

        // Ajoutez un écouteur d'événement clic pour fermer l'overlay en plein écran
        document.getElementById('close-fullscreen').addEventListener('click', function() {
            document.getElementById('fullscreen-overlay').style.display = 'none';
        });

        // Empêchez la propagation des clics à partir de l'image en plein écran
        document.getElementById('fullscreen-image-container').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }
});

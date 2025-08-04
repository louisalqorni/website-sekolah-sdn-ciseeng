// Initialize AOS (Animate On Scroll)
AOS.init({
    duration: 1000,
    once: true, // Whether animation should happen only once - while scrolling down
});

// Mobile Menu Toggle
const btnMenu = document.getElementById('btn-menu');
const mobileMenu = document.getElementById('mobile-menu');

if (btnMenu && mobileMenu) {
    btnMenu.addEventListener('click', () => {
        mobileMenu.classList.toggle('open');
        btnMenu.classList.toggle('open'); // Optional: Add class to hamburger for animation
    });

    // Close mobile menu when a link is clicked (optional)
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            btnMenu.classList.remove('open');
        });
    });
}


// Image Slider Functionality
let slideIndex = 1;
const slides = document.getElementsByClassName("slide");
const dots = document.getElementsByClassName("dot");
let slideInterval;

function showSlides(n) {
    let i;
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.opacity = "0";
        slides[i].classList.remove('active');
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    if (slides[slideIndex - 1]) { // Check if slide exists
        slides[slideIndex - 1].style.opacity = "1";
        slides[slideIndex - 1].classList.add('active');
    }
    if (dots[slideIndex - 1]) { // Check if dot exists
        dots[slideIndex - 1].className += " active";
    }
}

function plusSlides(n) {
    clearTimeout(slideInterval); // Clear current interval
    showSlides(slideIndex += n);
    startSlideShow(); // Restart interval
}

function currentSlide(n) {
    clearTimeout(slideInterval); // Clear current interval
    showSlides(slideIndex = n);
    startSlideShow(); // Restart interval
}

function autoSlides() {
    slideIndex++;
    if (slideIndex > slides.length) { slideIndex = 1; }
    showSlides(slideIndex);
    startSlideShow(); // Re-set interval
}

function startSlideShow() {
    // Only start if there are slides
    if (slides.length > 0) {
        slideInterval = setTimeout(autoSlides, 5000); // Change image every 5 seconds
    }
}

// Initial display and start slideshow if elements exist
document.addEventListener("DOMContentLoaded", () => {
    if (slides.length > 0) {
        showSlides(slideIndex);
        startSlideShow();
    }
});

// Dropdown menu for mobile (if hamburger is clicked, dropdown opens)
// This is handled by CSS :hover for desktop. For mobile, it's click based
// (already part of the default menu-mobile behavior). If you need nested dropdowns
// on mobile, it would require more specific JS.

// Function to handle active link highlighting (basic example)
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname.split('/').pop();
    const desktopLinks = document.querySelectorAll('.menu-desktop .menu-link');
    const mobileLinks = document.querySelectorAll('.menu-mobile a');

    desktopLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPath || (currentPath === '' && linkHref === 'index.html')) {
            link.classList.add('active');
        }
    });

    mobileLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPath || (currentPath === '' && linkHref === 'index.html')) {
            link.classList.add('active');
        }
    });
});

const menuToggle = document.getElementById('menu-toggle');
const nav = document.querySelector('nav ul');

menuToggle.addEventListener('click', () => {
    nav.classList.toggle('active');
});

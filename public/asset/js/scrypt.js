// cards-animation.js

document.addEventListener("DOMContentLoaded", () => {

    const cards = document.querySelectorAll(".product-card-custom");
    const buttons = document.querySelectorAll(".product-card-custom .btn");

    console.log("JS OK - Cards trouvées :", cards.length);

    // Animation d'apparition
    cards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";

        setTimeout(() => {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, 150 * index);
    });

    // Hover animation
    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-8px)";
            card.style.boxShadow = "0 12px 30px rgba(0,0,0,0.20)";
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
            card.style.boxShadow = "0 4px 15px rgba(0,0,0,0.12)";
        });
    });

    // Boutons animés
    buttons.forEach(btn => {
        btn.addEventListener("mouseenter", () => {
            btn.style.transform = "scale(1.05)";
        });
        btn.addEventListener("mouseleave", () => {
            btn.style.transform = "scale(1)";
        });
    });

});


    const input = document.getElementById('search');
    const cards = document.querySelectorAll('.card');

    input.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();

    cards.forEach(card => {
    const name = (card.dataset.name || '').toLowerCase();
    const description = (card.dataset.description || '').toLowerCase();

    // si q est vide -> montrer toutes les cards
    const visible = q === '' || name.includes(q) || description.includes(q);
    card.style.display = visible ? '' : 'none';
});
});
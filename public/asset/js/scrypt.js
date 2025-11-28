
document.addEventListener("DOMContentLoaded", () => {

    const cards = document.querySelectorAll(".product-card-custom");

    // Animation d'apparition au chargement (fade + slide)
    cards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "opacity 0.5s ease, transform 0.5s ease";

        setTimeout(() => {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, 150 * index); // effet cascade
    });

    // Animation hover JS (plus fluide que CSS uniquement)
    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-8px)";
            card.style.boxShadow = "0 12px 30px rgba(0,0,0,0.25)";
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
            card.style.boxShadow = "0 4px 15px rgba(0,0,0,0.12)";
        });
    });

    // Animation du bouton au survol
    const buttons = document.querySelectorAll(".product-card-custom .btn");

    buttons.forEach(btn => {
        btn.addEventListener("mouseenter", () => {
            btn.style.transform = "scale(1.05)";
            btn.style.transition = "transform 0.2s ease";
        });

        btn.addEventListener("mouseleave", () => {
            btn.style.transform = "scale(1)";
        });
    });

});



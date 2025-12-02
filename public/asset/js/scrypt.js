// cards-animation.js

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

//panier--------------------------------------------------------

// Helper : normalise (enlève accents) + passe en minuscule
function normalizeText(s) {
    if (!s) return '';
    return s
        .normalize('NFD')                   // sépare lettre + diacritiques
        .replace(/\p{Diacritic}/gu, '')     // supprime les diacritiques
        .toLowerCase();
}

const input = document.getElementById('search');
const cards = document.querySelectorAll('.card');

input.addEventListener('input', () => {
    const raw = input.value.trim();
    if (raw === '') {
        // champ vide : tout afficher
        cards.forEach(c => c.style.display = '');
        return;
    }

    // tokenisation des mots de recherche (ex: "savon rose" -> ["savon","rose"])
    const tokens = normalizeText(raw).split(/\s+/).filter(Boolean);

    cards.forEach(card => {
        const name = normalizeText(card.dataset.name || '');
        const description = normalizeText(card.dataset.description || '');
        const hay = name + ' ' + description; // on recherche dans l'ensemble

        // stratégie : tous les tokens doivent être présents au moins une fois
        const matchesAll = tokens.every(t => hay.includes(t));

        card.style.display = matchesAll ? '' : 'none';
    });
});
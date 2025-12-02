document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".card");
    const buttons = document.querySelectorAll(".card a.btn");

    // Animation au survol
    cards.forEach(card => {
        card.style.transition = "0.3s ease";

        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-8px)";
            card.style.boxShadow = "0 10px 20px rgba(0,0,0,0.25)";
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
            card.style.boxShadow = "0 4px 8px rgba(0,0,0,0.1)";
        });
    });

    // Animation du bouton
    buttons.forEach(btn => {
        btn.style.transition = "0.2s ease";

        btn.addEventListener("mouseenter", () => {
            btn.style.transform = "scale(1.05)";
        });

        btn.addEventListener("mouseleave", () => {
            btn.style.transform = "scale(1)";
        });

        // Effet de clic
        btn.addEventListener("click", e => {
            btn.style.transform = "scale(0.95)";
            setTimeout(() => {
                btn.style.transform = "scale(1)";
            }, 150);
        });
    });
});

/*
Footer js
 */

document.addEventListener("DOMContentLoaded", () => {
    const footer = document.querySelector("footer");
    const socialIcons = document.querySelectorAll(".social-icon");
    const footerLinks = document.querySelectorAll(".footer-links a");
    const titles = document.querySelectorAll("footer h2");

    /* --- ANIMATION D'APPARITION AU SCROLL --- */
    const showFooter = () => {
        const position = footer.getBoundingClientRect().top;
        const screen = window.innerHeight;

        if (position < screen - 100) {
            footer.style.opacity = "1";
            footer.style.transform = "translateY(0)";
        }
    };

    footer.style.opacity = "0";
    footer.style.transform = "translateY(40px)";
    footer.style.transition = "0.8s ease";

    window.addEventListener("scroll", showFooter);
    showFooter();

    /* --- ANIMATION DES LIENS --- */
    footerLinks.forEach(link => {
        link.style.transition = "color 0.3s ease";

        link.addEventListener("mouseenter", () => {
            link.style.color = "#ffc107"; // jaune Bootstrap
        });

        link.addEventListener("mouseleave", () => {
            link.style.color = "";
        });
    });

    /* --- ANIMATION DES ICÔNES SOCIALES --- */
    socialIcons.forEach(icon => {
        icon.style.transition = "transform 0.3s ease, color 0.3s ease";

        icon.addEventListener("mouseenter", () => {
            icon.style.transform = "scale(1.2) rotate(5deg)";
            icon.style.color = "#ffc107";
        });

        icon.addEventListener("mouseleave", () => {
            icon.style.transform = "scale(1)";
            icon.style.color = "";
        });

        // Effet clic
        icon.addEventListener("click", () => {
            icon.style.transform = "scale(0.9)";
            setTimeout(() => {
                icon.style.transform = "scale(1.2)";
            }, 100);
        });
    });

    /* --- ANIMATION DES TITRES --- */
    titles.forEach(title => {
        title.style.transition = "text-shadow 0.3s ease";

        title.addEventListener("mouseenter", () => {
            title.style.textShadow = "0 0 10px #ffc107";
        });

        title.addEventListener("mouseleave", () => {
            title.style.textShadow = "none";
        });
    });
});

/*
header js
 */
document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector("header");
    const navLinks = document.querySelectorAll("header nav a");
    const icons = document.querySelectorAll(".header-icon svg");
    const logo = document.querySelector("header img");

    /* --- APPARITION DOUCE DU HEADER --- */
    header.style.opacity = "0";
    header.style.transform = "translateY(-20px)";
    header.style.transition = "0.7s ease";

    setTimeout(() => {
        header.style.opacity = "1";
        header.style.transform = "translateY(0)";
    }, 150);

    /* --- HEADER ANIMÉ AU SCROLL --- */
    window.addEventListener("scroll", () => {
        if (window.scrollY > 60) {
            header.style.background = "rgba(255, 255, 255, 0.9)";
            header.style.boxShadow = "0 4px 12px rgba(0,0,0,0.2)";
            header.style.backdropFilter = "blur(5px)";
        } else {
            header.style.background = "transparent";
            header.style.boxShadow = "none";
            header.style.backdropFilter = "none";
        }
    });

    /* --- ANIMATION DES LIENS DE NAVIGATION --- */
    navLinks.forEach(link => {
        link.style.position = "relative";
        link.style.transition = "color 0.3s ease";

        // Barre d'animation sous le lien
        const underline = document.createElement("span");
        underline.style.position = "absolute";
        underline.style.bottom = "0";
        underline.style.left = "0";
        underline.style.width = "0%";
        underline.style.height = "3px";
        underline.style.background = "#0d6efd";
        underline.style.transition = "width 0.3s ease";
        link.appendChild(underline);

        link.addEventListener("mouseenter", () => {
            underline.style.width = "100%";
            link.style.color = "#0d6efd";
        });

        link.addEventListener("mouseleave", () => {
            underline.style.width = "0%";
            link.style.color = "";
        });
    });

    /* --- ANIMATION DES ICÔNES --- */
    icons.forEach(icon => {
        icon.style.transition = "0.3s ease";

        icon.addEventListener("mouseenter", () => {
            icon.style.transform = "scale(1.15)";
            icon.style.filter = "drop-shadow(0 0 6px #0d6efd)";
        });

        icon.addEventListener("mouseleave", () => {
            icon.style.transform = "scale(1)";
            icon.style.filter = "none";
        });

        // Effet clic
        icon.addEventListener("click", () => {
            icon.style.transform = "scale(0.9)";
            setTimeout(() => {
                icon.style.transform = "scale(1.15)";
            }, 180);
        });
    });

    /* --- ANIMATION DU LOGO --- */
    if (logo) {
        logo.style.transition = "0.4s ease";

        logo.addEventListener("mouseenter", () => {
            logo.style.transform = "rotate(8deg) scale(1.05)";
            logo.style.filter = "drop-shadow(0 0 8px #0d6efd)";
        });

        logo.addEventListener("mouseleave", () => {
            logo.style.transform = "rotate(0deg) scale(1)";
            logo.style.filter = "none";
        });
    }
});

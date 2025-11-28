<?php
include 'public/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savuinos - Luxe Pur pour Votre Peau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="../public/asset/CSS/accueil.css">
</head>
<body>
<div class="site-container">

    <section  class="p-3 mb-2 bg-secondary text-white">
        <div class="hero-content">
            <p class="tag-line">Naturel & Biologique</p>
            <h1>Luxe Pur pour Votre Peau</h1>
            <p class="text-lead">Découvrez notre collection de savons naturels et artisanaux, fabriqués avec amour et les meilleurs ingrédients biologiques.</p>
            <div>
                <a href="#"  class="btn btn-dark">Acheter Maintenant →</a>
                <a href="#" class="btn btn-dark">En Savoir Plus</a>
            </div>
        </div>
    </section>
    <section class="p-4 mb-5 bg-primary text-white hero-section-custom" >
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-white-50">
            <div>
                <img src="public/asset/img/img1.jpg" height="300px" width="500px" alt="Savon naturel sur un support en bois" class="img-thumbnail rounded shadow-lg">
            </div>
            <div class="ms-4" style="max-width: 400px;">
                <p class="small text-white-75">Ce savon artisanal est délicatement formulé avec des ingrédients naturels<br>et biologiques. Sa mousse douce nettoie la peau en profondeur tout en la laissant hydratée et<br> parfumée d’un subtil arôme floral. Idéal pour un moment de détente quotidien, il respecte tous les types de peau,<br> même les plus sensibles.</p>
            </div>
        </div>
    </section>
    <div class="d-flex justify-content-around features-section-container">
        <div class="feature-card">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-leaf-fill feature-icon" viewBox="0 0 16 16">
                <path d="M1.4 1.7c.217.289.65.84 1.725 1.274 1.093.44 2.885.774 5.834.528 2.02-.168 3.431.51 4.326 1.556C14.161 6.082 14.5 7.41 14.5 8.5q0 .344-.027.734C13.387 8.252 11.877 7.76 10.39 7.5c-2.016-.288-4.188-.445-5.59-2.045-.142-.162-.402-.102-.379.112.108.985 1.104 1.82 1.844 2.308 2.37 1.566 5.772-.118 7.6 3.071.505.8 1.374 2.7 1.75 4.292.07.298-.066.611-.354.715a.7.7 0 0 1-.161.042 1 1 0 0 1-1.08-.794c-.13-.97-.396-1.913-.868-2.77C12.173 13.386 10.565 14 8 14c-1.854 0-3.32-.544-4.45-1.435-1.124-.887-1.889-2.095-2.39-3.383-1-2.562-1-5.536-.65-7.28L.73.806z"/>
            </svg>
            <h4 class="fw-bold">100% Naturel</h4>
            <p class="text-muted">Fabriqué avec des ingrédients biologiques de la nature.</p>
        </div>
        <div class="feature-card">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-suit-heart-fill feature-icon" viewBox="0 0 16 16">
                <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1"/>
            </svg>
            <h4 class="fw-bold">Doux pour la Peau</h4>
            <p class="text-muted">Formules douces adaptées à tous les types de peau.</p>
        </div>
        <div class="feature-card">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-hand-thumbs-up-fill feature-icon" viewBox="0 0 16 16">
                <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
            </svg>
            <h4 class="fw-bold">Fait Main</h4>
            <p class="text-muted">Chaque savon est fabriqué avec soin à la main.</p>
        </div>
        <div class="feature-card">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-vinyl-fill feature-icon" viewBox="0 0 16 16">
                <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4m0 3a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4 8a4 4 0 1 0 8 0 4 4 0 0 0-8 0"/>
            </svg>
            <h4 class="fw-bold">Éco-Responsable</h4>
            <p class="text-muted">Emballage et pratiques durables.</p>
        </div>
    </div>
    <section class="p-3 mb-2 bg-primary text-white">
        <h2 class="text-center fw-bold">Nos Produits Phares</h2>
        <p class="section-subtitle text-center text-white-50">Découvrez nos savons naturels les plus vendus</p>

        <div class="container mt-4">
            <div class="row g-4">
                <div class="col-md-3 col-6"> <div class="card shadow-sm rounded-4 h-20 product-card-custom"> <div class="product-img-container">
                            <img src="public/asset/img/img2.jpg" class="card-img-top" alt="Savon Rêves de Lavande">
                        </div>
                        <div class="card-body p-3">
                            <span class="badge bg-warning text-dark mb-1">Floral</span> <h6 class="card-title fw-bold mb-1">Savon Rêves de Lavande</h6> <p class="fw-bold fs-5 text-primary mb-2">12,99 €</p> <a href="#" class="btn btn-primary btn-sm w-100">Ajouter au Panier</a> </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card shadow-sm rounded-4 h-100 product-card-custom">
                        <div class="product-img-container">
                            <img src="public/asset/img/img3.jpg" class="card-img-top" alt="Savon Miel & Avoine">
                        </div>
                        <div class="card-body p-3">
                            <span class="badge bg-warning text-dark mb-1">Exfoliant</span>
                            <h6 class="card-title fw-bold mb-1">Savon Miel & Avoine</h6>
                            <p class="fw-bold fs-5 text-primary mb-2">14,99 €</p>
                            <a href="#" class="btn btn-primary btn-sm w-100">Ajouter au Panier</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card shadow-sm rounded-4 h-100 product-card-custom">
                        <div class="product-img-container">
                            <img src="public/asset/img/img4.jpg" class="card-img-top" alt="Détox Charbon Actif">
                        </div>
                        <div class="card-body p-3">
                            <span class="badge bg-warning text-dark mb-1">Détox</span>
                            <h6 class="card-title fw-bold mb-1">Détox Charbon Actif</h6>
                            <p class="fw-bold fs-5 text-primary mb-2">15,99 €</p>
                            <a href="#" class="btn btn-primary btn-sm w-100">Ajouter au Panier</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card shadow-sm rounded-4 h-100 product-card-custom">
                        <div class="product-img-container">
                            <img src="public/asset/img/img5.jpg" class="card-img-top" alt="Luxe Pétales de Rose">
                        </div>
                        <div class="card-body p-3">
                            <span class="badge bg-warning text-dark mb-1">Floral</span>
                            <h6 class="card-title fw-bold mb-1">Luxe Pétales de Rose</h6>
                            <p class="fw-bold fs-5 text-primary mb-2">16,99 €</p>
                            <a href="#" class="btn btn-primary btn-sm w-100">Ajouter au Panier</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 mb-3"> <a href="#" class="btn btn-warning fw-bold">Voir Tous les Produits →</a> </div>
        </div>
    </section>

</div>
<img
    src="https://media.giphy.com/media/v1.Y2lkPWVjZjA1ZTQ3cTM4MDF1azlxdWd0MGR6dDRtMzE0N3JxMHJmcXVjMjhweGt1cTJzZyZlcD12MV9naWZzX3NlYXJjaCZjdD1n/1bChcrzVV9LSB7kFKD/giphy.gif"
    class="img-fluid rounded shadow-lg d-block mx-auto"
    alt="Démonstration animée d'un savon Savuinos"
    style="max-width: 450px;"
/>
<section class="cta-section">
    <div class="cta-content">
        <p class="tag-line-cta">Prêt(e) à Transformer Vos Soins de Peau ?</p>
        <h2 class="text-white fw-bold">Rejoignez des milliers de clients satisfaits qui font confiance à Savuinos</h2>
        <a href="#" class="btn btn-cta">Commencer Mes Achats →</a>
    </div>
</section>
<script src="./public/asset/js/scrypt.js"></script>
<?php
include 'public/includes/footer.php';
?>

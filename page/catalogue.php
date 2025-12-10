<?php

$panier = $_SESSION['panier'] ?? [];
?>

<section class="border">

    <div class="filters" style="display:flex; gap:10px; margin-bottom:20px;">
        <input id="search" type="search" placeholder=" 🔍  Recherche un produit...">

        <select id="price">
            <option value="">Tous les prix</option>
            <option value="0-5">0 → 5 €</option>
            <option value="5-10">5 → 10 €</option>
            <option value="10-20">10 → 20 €</option>
            <option value="20-9999">20 € et +</option>
        </select>
    </div>

    <div class="container mt-4">
        <div id="products" class="row">
            <!-- Résultats AJAX ici -->
        </div>
    </div>

</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const search = document.getElementById("search");
        const price = document.getElementById("price");
        const productsContainer = document.getElementById("products");

        function loadProducts() {
            const query = search.value;
            const priceRange = price.value;

            fetch("public/includes/search_products.php?search=" + query + "&price=" + priceRange)
                .then(res => res.text())
                .then(data => {
                    productsContainer.innerHTML = data;
                });
        }

        search.addEventListener("input", loadProducts);
        price.addEventListener("change", loadProducts);

        loadProducts(); // Charger au début
    });
</script>
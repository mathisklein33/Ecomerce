<?php

if (!$conn) {
    die("Erreur de connexion MySQL : " . mysqli_connect_error());
}

$sql = "
SELECT *
FROM produit
";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Erreur SQL : ' . mysqli_error($conn));
}
if (isset($_SESSION['panier'])){
    $panier = $_SESSION['panier'] ?? [];
}

//$panier = $panier + $add;
?>

<input id="search" type="search" placeholder="Recherche...">



<!--Creation des cards-->
<?php while($row = mysqli_fetch_assoc($result)) : ?>
    <div class="card"
         data-name="<?= htmlspecialchars($row['nom'], ENT_QUOTES) ?>"
         data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>">

        <h5><?= htmlspecialchars($row['nom']) ?></h5>

        <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
        <p><strong>Prix :</strong> <?= htmlspecialchars($row['prix']) ?> €</p>
        <div>
            <a href="https://localhost/savouinos/?page=produits&idproduit=<?= $row['idproduit'] ?>">Détails</a>
        </div>
        <div>
            <a href="http://localhost/savouinos/public/includes/panier_add.php?id=<?= $row['idproduit'] ?>">
                Ajouter au panier
            </a>
        </div>
    </div>
<?php endwhile; ?>

<script>
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
</script>
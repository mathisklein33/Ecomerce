<?php
require '../config/config.php';

$search = $_GET['search'] ?? '';
$price = $_GET['price'] ?? '';

$sql = "SELECT * FROM produit WHERE 1";
$params = [];
$types = "";

// Recherche par nom
if (!empty($search)) {
    $sql .= " AND nom LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// Filtre prix
if (!empty($price)) {
    list($min, $max) = explode("-", $price);
    $sql .= " AND prix BETWEEN ? AND ?";
    $params[] = $min;
    $params[] = $max;
    $types .= "dd";
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Aucun produit trouvé.</p>";
    exit;
}

while ($row = $result->fetch_assoc()) : ?>

    <div class="col-md-4 col-lg-3">
        <div class="product-card">

            <img src="/savouinos/public/asset/img/<?= htmlspecialchars($row['image']) ?>"
                 alt="<?= htmlspecialchars($row['nom']) ?>">

            <h5><?= htmlspecialchars($row['nom']) ?></h5>

            <p class="price-tag"><?= htmlspecialchars($row['prix']) ?> €</p>

            <div class="product-buttons">
                <a class="btn-details"
                   href="https://localhost/savouinos/?page=produits&idproduit=<?= $row['idproduit'] ?>">
                    Voir détails
                </a>

                <a class="btn-add"
                   href="http://localhost/savouinos/public/includes/panier_add.php?id=<?= $row['idproduit'] ?>">
                    Ajouter au panier 🛒
                </a>
            </div>

        </div>
    </div>

<?php endwhile; ?>

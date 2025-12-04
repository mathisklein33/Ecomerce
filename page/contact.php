<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>

</body>
</html>


<div class="container py-5">

    <h1 class="text-center fw-bold mb-4">Contact & Informations</h1>

    <p class="text-center text-muted mb-5">
        Une question ? Besoin d’aide ? Notre équipe est là pour vous accompagner.
    </p>

    <div class="row g-4 justify-content-center">

        <!-- Coordonnées -->
        <div class="col-md-5">
            <div class="p-4 rounded shadow-sm bg-white h-100">
                <h4 class="fw-bold mb-3">📞 Nous Contacter</h4>

                <p><strong>Email :</strong> contact@savouinos.fr</p>
                <p><strong>Téléphone :</strong> +33 6 45 12 89 33</p>
                <p><strong>Adresse :</strong><br>
                    6 rue Générale Saraille<br>
                    76000 Rouen, France
                </p>

                <p class="mt-3 text-muted small">
                    Nous répondons généralement en moins de 24h.
                </p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="col-md-5">
            <div class="p-4 rounded shadow-sm bg-light h-100">
                <h4 class="fw-bold mb-3">✉️ Envoyer un message</h4>

                <form>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" placeholder="Votre nom">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Votre email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" placeholder="Votre message..."></textarea>
                    </div>

                    <button class="btn btn-primary w-100">Envoyer</button>
                </form>
            </div>
        </div>

    </div>

    <!-- Carte Google Map -->
    <div class="mt-5">
        <h4 class="fw-bold mb-3 text-center">📍 Localisation</h4>
        <div class="ratio ratio-16x9 shadow rounded overflow-hidden">
            <iframe
                src="https://maps.google.com/maps?q=6%20Rue%20du%20G%C3%A9n%C3%A9ral%20Sarrail%2C%20Rouen&t=&z=15&ie=UTF8&iwloc=&output=embed"
                allowfullscreen>
            </iframe>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


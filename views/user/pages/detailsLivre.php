<?php
session_start();
require_once '../../../includes/database.php';
require_once '../../../includes/function.php';

// Vérification si un ID de livre est passé
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_livre = intval($_GET['id']);

    // Requête pour récupérer les détails du livre
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = :id_livre");
    $stmt->execute(['id_livre' => $id_livre]);
    $livre = $stmt->fetch();

    // Si le livre n'existe pas
    if (!$livre) {
        $_SESSION['error'] = "Le livre n'existe pas.";
        header('Location: ./catalogue.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Aucun livre sélectionné.";
    header('Location: ./catalogue.php');
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="
    https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.min.css
    " rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <a href="./catalogue.php" class="d-flex align-items-center gap-3 text-decoration-none text-black fs-4">
                    <i class="ri-arrow-left-s-line"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-sm-6">
                <img src="../../../uploads/couvertures/<?= htmlspecialchars($livre['couverture']); ?>" class="img-fluid rounded" alt="Couverture du livre">
            </div>
            <div class="col-12 col-sm-6">
                <h5 class="card-title"><?= htmlspecialchars($livre['titre']); ?></h5>
                <p class="card-text"><strong>Auteur:</strong> <?= htmlspecialchars($livre['auteur']); ?></p>
                <p class="card-text"><strong>Catégorie:</strong> <?= htmlspecialchars($livre['categorie']); ?></p>
                <p class="card-text"><strong>ISBN:</strong> <?= htmlspecialchars($livre['ISBN']); ?></p>
                <p class="card-text mt-4"><strong>Description:</strong> <?= htmlspecialchars($livre['description']); ?></p>
                <p class="card-text"><strong>Statut:</strong> <?= $livre['status'] == 1 ? 'Disponible' : 'Emprunté'; ?></p>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
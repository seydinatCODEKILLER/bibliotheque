<?php
session_start();
require_once "../../../includes/database.php";
require_once "../../../includes/function.php";
require_once "../../../includes/verified.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../auth/connexion.php');
    exit();
}

$error = $_SESSION["error"] ?? [];
$success = $_SESSION['success'] ?? [];
unset($_SESSION['success'], $_SESSION["error"]);



// Récupération des catégories
$categories = getCategories($pdo);
$status = getStatus($pdo);

// Récupération des livres (filtrés ou non)
$categorie_id = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$livres = getLivres($pdo, $categorie_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mes livres</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <link href="
    https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.min.css
    " rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body class="">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-2 sidebar d-none d-lg-flex flex-column justify-content-between col-lg-2 bg-white p-0" style="height: 100vh; position: fixed; left: 0; bottom: 0">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <img src="../../../assets/img/logo.png" class="img-fluid d-none d-lg-block" style="height: 50px" alt="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <ul class="d-flex flex-column list-unstyled">
                                <li>
                                    <a href="../../../admin_dashboard.php" class="sidebar-link text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="m-0">
                                    <a href="./addLivre.php" class="sidebar-link text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Ajouts livres</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#" class="sidebar-link text-decoration-none text-black active d-none d-lg-flex">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Mes livres</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./abonnee.php" class="sidebar-link text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-group-line fs-4"></i>
                                        <span>Abonnées</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="list-unstyled">
                            <li>
                                <a href="../../../controllers/admin/deconnexion.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                    <i class="ri-logout-box-line fs-4"></i>
                                    <span>Deconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-10 contente position-relative" style="height: 100vh">
                <div class="container d-flex flex-column justify-content-between" style="min-height: 100vh">
                    <div>
                        <div class="row">
                            <div class="col-12 bg-white shadow-sm d-flex align-items-center justify-content-between" style="height: 12vh">
                                <div class="">
                                    <div class="fs-4 d-flex justify-content-center align-items-center rounded-circle menu" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">
                                        <i class="ri-menu-2-fill"></i>
                                    </div>
                                </div>
                                <div class="d-none d-sm-block">
                                    <p class="m-0 fw-bold">Administrateur</p>
                                </div>
                                <div class="d-block d-sm-none mt-4">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a href="../../../controllers/admin/deconnexion.php" class="sidebar-link text-decoration-none text-black">
                                                <i class="ri-logout-box-line fs-4"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-12 bg-white shadow-sm d-flex flex-column justify-content-between" style="min-height: 88vh;">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <h4 class="mb-4">Liste des livres</h4>
                                    </div>
                                    <div class="col-12 col-sm-5 col-lg-5">
                                        <form method="get" action="myBooks.php" class="mb-4 d-flex gap-3">
                                            <select name="categorie" class="form-control">
                                                <option value="">Toutes les catégories</option>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?= $category['id_categorie'] ?>">
                                                        <?= htmlspecialchars($category['libelle']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="">
                                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 overflow-x-auto">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Couverture</th>
                                                    <th>Nom</th>
                                                    <th>Auteur</th>
                                                    <th>Catégorie</th>
                                                    <th>ISBN</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($livres as $k => $livre) : ?>
                                                    <tr>
                                                        <td><img src="../../../uploads/couvertures/<?= htmlspecialchars($livre['couverture']) ?>" class="rounded" alt="Couverture" style="width: 40px; height: 40px;"></td>
                                                        <td><?= htmlspecialchars($livre['titre']) ?></td>
                                                        <td><?= htmlspecialchars($livre['auteur']) ?></td>
                                                        <td>
                                                            <?php foreach ($categories as $categorie) : ?>
                                                                <?php if ($categorie["id_categorie"] == $livre["categorie"]) : ?>
                                                                    <?= $categorie["libelle"] ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($livre['ISBN']) ?></td>
                                                        <td>
                                                            <?php foreach ($status as $statu) : ?>
                                                                <?php if ($statu["id_status"] == $livre["status"]) : ?>
                                                                    <?= $statu["libelle"] ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td>
                                                            <a href="editLivre.php?id=<?= $livre['id_livre'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                            <a data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $k ?>" class="btn btn-danger btn-sm">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="staticBackdrop<?= $k ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?= $k ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h3 class="text-center">Confirmer la supression</h3>
                                                                    <div class="d-flex gap-3 justify-content-center">
                                                                        <a href="../../../controllers/admin/deleteLivre.php?id=<?= $livre['id_livre'] ?>"><button class="btn bg-danger text-white">Supprimer</button></a>
                                                                        <button class="btn bg-primary text-white" data-bs-dismiss="modal">Annuler</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row border-top mt-5">
                                    <div class="col-12 py-2">
                                        <p class="m-0 text-center"> Tous droits réservés. | © 2024 BIBLIO.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($success)) : ?>
                    <?= notification("ri-checkbox-circle-fill", "success", $success); ?>
                <?php endif; ?>
                <?php if (!empty($error)) : ?>
                    <?= notification("ri-alert-fill", "danger", $error); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="offcanvas offcanvas-start d-block d-lg-none p-0" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <div class="d-flex flex-column justify-content-between">
                <div class="row">
                    <div class="col-12 px-4 py-3">
                        <img src="../../../assets/img/logo.png" class="img-fluid d-block d-lg-none" style="height: 60px" alt="" />
                    </div>
                </div>
                <div class="row" style="margin-top: 200px;">
                    <div class="col-12">
                        <nav>
                            <ul class="d-flex flex-column gap-2 list-unstyled">
                                <li>
                                    <a href="../../../admin_dashboard.php" class="sidebar-link text-decoration-none text-black ">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./addLivre.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Ajout Livres</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./myBooks.php" class="sidebar-link text-decoration-none active text-black ">
                                        <i class="ri-user-line fs-4"></i>
                                        <span>Mes livres</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./abonnee.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-group-line fs-4"></i>
                                        <span>Abonnées</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row" style="margin-top: 270px;">
                    <div class="col-12">
                        <ul class="list-unstyled">
                            <li>
                                <a href="../../../controllers/admin/deconnexion.php" class="sidebar-link text-decoration-none text-black">
                                    <i class="ri-logout-box-line fs-4"></i>
                                    <span>Deconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="../../../assets/javascript/home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
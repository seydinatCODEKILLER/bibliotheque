<?php
session_start();
require_once "../../../includes/database.php";
require_once "../../../includes/function.php";
require_once "../../../includes/verified.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/connexion.php');
    exit();
}

$error = $_SESSION["error"] ?? [];
$success = $_SESSION['success'] ?? [];
unset($_SESSION['success'], $_SESSION["error"]);

$user_id = $_SESSION['user_id'];
$user = getUserWithId($pdo, $user_id);
$notification_count = getCountNotif($pdo, $user_id);
$livres = searchParamsToGetBooks($pdo, $_GET);
$categories = getCategories($pdo);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Catalogues</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="../../../assets/css/home.css">
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
                            <ul class="d-flex flex-column gap-2 list-unstyled">
                                <li>
                                    <a href="../../../index.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./catalogue.php" class="sidebar-link active text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Catalogues</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./profil.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-user-line fs-4"></i>
                                        <span>Profils</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./notification.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-notification-3-line fs-4"></i>
                                        <span>Notifications</span>
                                        <?php if ($notification_count > 0) : ?>
                                            <span class="badge bg-danger"><?= $notification_count ?></span>
                                        <?php endif; ?>
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
                                <a href="controllers/user/deconnexion.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                    <i class="ri-logout-box-line fs-4"></i>
                                    <span>Deconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-10 contente position-relative" style="height: 100vh">
                <div class="container d-flex flex-column justify-content-between" style="height: 100vh">
                    <div>
                        <div class="row">
                            <div class="col-12 bg-white shadow-sm d-flex align-items-center justify-content-between border-bottom" style="height: 12vh">
                                <div class="">
                                    <div class="fs-4 d-flex justify-content-center align-items-center rounded-circle menu" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">
                                        <i class="ri-menu-2-fill"></i>
                                    </div>
                                </div>
                                <div>
                                    <form class="d-none d-sm-flex gap-1" role="search" method="get">
                                        <input class="form-control border border-end-0 bg-light rounded-start" type="search" placeholder="Recherche..." name="search" style="width: 340px" />
                                        <button class="border rounded-end bg-primary p-2" type="submit">
                                            <i class="ri-search-line text-white"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="d-none d-sm-flex align-items-center gap-3">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="d-flex flex-column">
                                            <p class="m-0 fw-bold d-flex justify-content-end">Mon compte</p>
                                            <li class="nav-item list-unstyled">
                                                <span><?= $user["prenom"] ?> <?= $user["nom"] ?></span>
                                            </li>
                                        </div>
                                        <div class="rounded-circle" style="height: 50px; width: 50px;background-image:url(../../../uploads/profiles/<?= $user["profil"] ?>);background-size:cover;background-position:center;background-repeat:no-repeat">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-block d-sm-none mt-4">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a href="../../../controllers/user/deconnexion.php" class="sidebar-link text-decoration-none text-black">
                                                <i class="ri-logout-box-line fs-4"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between" style="min-height: 88vh;">
                            <div class="col-12 col-lg-12 bg-white shadow-sm d-flex flex-column justify-content-between">
                                <div class="row mt-4">
                                    <div class="col-12 d-flex justify-content-end">
                                        <form method="get" action="" class="mb-4 d-flex gap-3">
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
                                <div class="row d-flex gap-lg-3 gap-0">
                                    <?php foreach ($livres as $k => $livre) : ?>
                                        <div class="col-12 col-sm-4 col-lg-3 mt-4 shadow-sm rounded">
                                            <div style="background-image: url(../../../uploads/couvertures/<?= $livre["couverture"] ?>);background-size:cover;background-repeat:no-repeat;background-position:center;height:35vh" class="w-100"></div>
                                            <div class="p-2">
                                                <div class="d-flex justify-content-end">
                                                    <?php if ($livre["status"] == 1) : ?>
                                                        <div style="width: 35px;height:35px" class="bg-success d-flex justify-content-center align-items-center fw-bold text-white rounded-circle">D</div>
                                                    <?php else : ?>
                                                        <div style="width: 35px;height:35px" class="bg-danger d-flex justify-content-center align-items-center fw-bold text-white rounded-circle">E</div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="py-3">
                                                    <p class="m-0"><?= $livre["titre"] ?></p>
                                                </div>
                                                <span class="mt-3 p-1 bg-warning-subtle"><?= $livre["auteur"] ?></span>
                                                <div class="d-flex align-items-center justify-content-between mt-3">
                                                    <a data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $k ?>" class=" btn bg-info text-white">Reserver</a>
                                                    <i class="ri-arrow-right-circle-line fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="staticBackdrop<?= $k ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?= $k ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdrop<?= $k ?>">Réserver le livre</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <img src="../../../uploads/couvertures/<?= htmlspecialchars($livre["couverture"]) ?>" alt="<?= htmlspecialchars($livre["titre"]) ?>" style="width: 100%; height: 40vh;" class="rounded img-fluid">
                                                            <h4 class="mt-2"><?= htmlspecialchars($livre["titre"]) ?></h4>
                                                            <p><?= htmlspecialchars($livre["auteur"]) ?></p>
                                                        </div>
                                                        <form action="../../../controllers/user/reserveLivre.php" method="POST">
                                                            <input type="hidden" name="id_livre" value="<?= $livre['id_livre'] ?>">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="form-group col-5">
                                                                    <label for="date_debut" class="form-label">Date de début</label>
                                                                    <input type="date" name="date_debut" class="form-control">
                                                                </div>
                                                                <div class="form-group col-5">
                                                                    <label for="date_fin" class="form-label">Date de fin</label>
                                                                    <input type="date" name="date_fin" class="form-control">
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-3 w-100">Réserver</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
                                    <a href="../../../index.php" class="sidebar-link text-decoration-none text-black ">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./catalogue.php" class="sidebar-link text-decoration-none active text-black">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Catalogues</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./profil.php" class="sidebar-link text-decoration-none text-black ">
                                        <i class="ri-user-line fs-4"></i>
                                        <span>Profils</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./notification.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-notification-3-line fs-4"></i>
                                        <span>Notifications</span>
                                        <?php if ($notification_count > 0) : ?>
                                            <span class="badge bg-danger"><?= $notification_count ?></span>
                                        <?php endif; ?>
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
                                <a href="../../../controllers/user/deconnexion.php" class="sidebar-link text-decoration-none text-black">
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
    <script src="../../../assets/javascript/catalogue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
<?php
session_start();
require_once "includes/database.php";
require_once "includes/function.php";
require_once "includes/verified.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: views/user/auth/connexion.php');
    exit();
}

$success = $_SESSION["success"] ?? [];
$user_id = $_SESSION['user_id'];
$user = getUserWithId($pdo, $user_id);
$livres = getThreeFirstBooks($pdo);
$notification_count = getCountNotif($pdo, $user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Details - livres</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="assets/css/home.css">
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
                        <img src="assets/img/logo.png" class="img-fluid d-none d-lg-block" style="height: 50px" alt="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <ul class="d-flex flex-column gap-2 list-unstyled">
                                <li>
                                    <a href="#" class="sidebar-link text-decoration-none text-black active d-none d-lg-block">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/catalogue.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Catalogues</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/emprunt.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/profil.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-user-line fs-4"></i>
                                        <span>Profils</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/notification.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
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
                                <div class="d-none d-sm-flex align-items-center gap-3">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="d-flex flex-column">
                                            <p class="m-0 fw-bold d-flex justify-content-end">Mon compte</p>
                                            <li class="nav-item list-unstyled">
                                                <span><?= $user["prenom"] ?> <?= $user["nom"] ?></span>
                                            </li>
                                        </div>
                                        <div class="rounded-circle" style="height: 50px; width: 50px;background-image:url(uploads/profiles/<?= $user["profil"] ?>);background-size:cover;background-position:center;background-repeat:no-repeat">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-block d-sm-none mt-4">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a href="controllers/user/deconnexion.php" class="sidebar-link text-decoration-none text-black">
                                                <i class="ri-logout-box-line fs-4"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class="col-12 col-lg-12 bg-white shadow-sm">
                                <div class="row py-4 d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-sm-8 col-lg-7 d-flex flex-column gap-4 mb-5 mb-lg-0">
                                        <h3>Bienvenue sur biblio <span class="nom"><?= $user["prenom"] ?></span></h3>
                                        <p class="m-0">
                                            Bienvenue sur Biblio, votre application de gestion de bibliothèque en ligne. Biblio vous permet de parcourir et d'emprunter des livres de manière simple et efficace, tout en offrant aux administrateurs les outils nécessaires pour gérer les collections et les abonnés. Profitez d'une expérience fluide pour découvrir et gérer vos lectures préférées !
                                        </p>
                                        <div>
                                            <a href="#" class="btn text-white link-catalogue">Voir nos catalogues</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 col-lg-4 mb-5 mb-lg-0">
                                        <img src="assets/img/hero.png" class="img-fluid" alt="hero">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                                        <div class="bordure"></div>
                                        <h3 class="mt-3">Nos livres du moments</h3>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <?php foreach ($livres as $k => $livre) : ?>
                                        <div class="col-12 col-sm-4">
                                            <div class="card w-100 shadow-sm">
                                                <img src="uploads/couvertures/<?= $livre["couverture"] ?>" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= $livre["titre"] ?></h5>
                                                    <p class="card-text"><?= $livre["auteur"] ?></p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <a href="views/user/pages/detailsLivre.php?id=<?= $livre["id_livre"] ?>" class="btn bg-info text-white">Découvrir</a>
                                                        <i class="ri-arrow-right-circle-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                                        <div class="bordure"></div>
                                        <h3 class="mt-3">Evenements</h3>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <p class="m-0 text-center">
                                            Dans le cadre de notre projet "Biblio", la section "Événements pour les sciences de la lecture" vise à promouvoir la passion pour la lecture et la littérature à travers une série d'événements éducatifs et culturels. Cette initiative propose des rencontres avec des auteurs, des ateliers de lecture, des discussions thématiques et des conférences, offrant aux participants une opportunité unique d'explorer et de célébrer le monde des livres et des sciences de la lecture.
                                        </p>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <video src="assets/img/video/video1.mp4" style="height: 55vh;" class="object-fit-cover w-100 rounded" loop autoplay muted></video>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <video src="assets/img/video/video2.mp4" style="height: 55vh;" class="object-fit-cover w-100 rounded" controls></video>
                                    </div>
                                    <div class="col-12 d-block d-sm-none d-lg-block col-sm-6 col-lg-4">
                                        <video src="assets/img/video/video3.mp4" style="height: 55vh;" class="object-fit-cover w-100 rounded" controls></video>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-between mt-5">
                                    <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                                        <div class="bordure"></div>
                                        <h3 class="mt-3">Feeds</h3>
                                    </div>
                                </div>
                                <div class="row mt-5 bg-light p-3">
                                    <div class="col-12 col-sm-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <p class="m-0 fs-3">+1000 livres</p>
                                            <i class="ri-book-open-line" style="font-size: 70px;"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <p class="m-0 fs-3">120K Abonnées</p>
                                            <i class="ri-group-line" style="font-size: 70px;"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <p class="m-0 fs-3">10 Bibliotheques</p>
                                            <i class="ri-building-line" style="font-size: 70px;"></i>
                                        </div>
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
                        <img src="assets/img/logo.png" class="img-fluid d-block d-lg-none" style="height: 60px" alt="" />
                    </div>
                </div>
                <div class="row" style="margin-top: 200px;">
                    <div class="col-12">
                        <nav>
                            <ul class="d-flex flex-column gap-2 list-unstyled">
                                <li>
                                    <a href="#" class="sidebar-link text-decoration-none active text-black ">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/catalogue.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Catalogues</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/emprunt.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/profil.php" class="sidebar-link text-decoration-none text-black ">
                                        <i class="ri-user-line fs-4"></i>
                                        <span>Profils</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="views/user/pages/notification.php" class="sidebar-link text-decoration-none text-black">
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
                                <a href="controllers/user/deconnexion.php" class="sidebar-link text-decoration-none text-black">
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
    <script src="assets/javascript/home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
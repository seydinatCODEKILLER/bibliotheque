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


$user_id = $_SESSION['user_id'];
$selected_status = isset($_GET['status']) ? $_GET['status'] : '';
$user = getUserWithId($pdo, $user_id);
$livres = getLivres($pdo);
$notification_count = getCountNotif($pdo, $user_id);
$emprunts = getEmpruntsUser($pdo, $selected_status, $user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mes emprunts</title>
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
                                    <a href="./catalogue.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Catalogues</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none active text-black d-none d-lg-block">
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
                                <a href="../../../controllers/user/deconnexion.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
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
                                <div class="row d-flex justify-content-between mt-4">
                                    <div class="col-12 col-sm-8 col-lg-4">
                                        <h4 class="mb-4">Liste des Emprunts</h4>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <form method="GET" action="emprunts.php" class="d-flex gap-2 align-items-center">
                                            <div>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">Tous</option>
                                                    <option value="en cours" <?php if ($selected_status == 'en cours') echo 'selected'; ?>>En cours</option>
                                                    <option value="terminé" <?php if ($selected_status == 'terminé') echo 'selected'; ?>>Terminé</option>
                                                    <option value="échu" <?php if ($selected_status == 'échu') echo 'selected'; ?>>Échu</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn bg-primary text-white">Filtrer</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 overflow-x-auto">
                                        <table class="table table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>Livre</th>
                                                    <th>Date début</th>
                                                    <th>Date fin</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($emprunts)) : ?>
                                                    <?php foreach ($emprunts as $emprunt) : ?>
                                                        <tr>
                                                            <td>
                                                                <?php foreach ($livres as $livre) : ?>
                                                                    <?php if ($livre["id_livre"] === $emprunt["id_livre"]) : ?>
                                                                        <?= $livre["titre"] ?>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <td><?= $emprunt['date_debut'] ?></td>
                                                            <td><?= $emprunt['date_fin'] ?></td>
                                                            <td style="color: 
                                                        <?php
                                                        if ($emprunt['status'] == 'en cours') echo 'orange';
                                                        elseif ($emprunt['status'] == 'terminé') echo 'green';
                                                        elseif ($emprunt['status'] == 'échu') echo 'red';
                                                        ?>">
                                                                <?= $emprunt['status'] ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="4">Aucun emprunt trouvé.</td>
                                                    </tr>
                                                <?php endif; ?>
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
                        <img src="../../../assets/img/logo.png" class="img-fluid" style="height: 60px" alt="" />
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
                                    <a href="./catalogue.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Catalogues</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none active text-black">
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
    <script src="../../../assets/javascript/home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
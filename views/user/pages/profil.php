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

$success = $_SESSION['success'] ?? [];
$error = $_SESSION["error"] ?? [];
unset($_SESSION['success'], $_SESSION['error']);

$user_id = $_SESSION['user_id'];
$user = getUserWithId($pdo, $user_id);
$notification_count = getCountNotif($pdo, $user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profils</title>
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
                                    <a href="./emprunt.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                        <i class="ri-shopping-bag-line fs-4"></i>
                                        <span>Emprunts</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./profil.php" class="sidebar-link text-decoration-none active text-black d-none d-lg-block">
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
                                <div class="row mt-5 d-flex flex-column align-items-center">
                                    <div class="col-12 d-flex flex-column flex-lg-rows align-items-center gap-3">
                                        <div class="rounded-circle border" style="height: 200px; width: 200px;background-image:url(../../../uploads/profiles/<?= $user["profil"] ?>);background-size:cover;background-position:center;background-repeat:no-repeat"></div>
                                        <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                                            <p class="m-0 fw-bold"><?= $user["prenom"] ?> <?= $user["nom"] ?></p>
                                            <form method="POST" action="../../../controllers/user/updatePhoto.php" enctype="multipart/form-data" class="d-flex flex-column justify-content-center align-items-center">
                                                <div class="form-group">
                                                    <input type="file" id="photo" class="form-control" name="photo">
                                                </div>
                                                <button type="submit" class="btn btn-primary mt-2">Modifier Photo</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <form method="POST" action="../../../controllers/user/updateInfo.php">
                                            <div class="row d-flex flex-column gap-3">
                                                <div class="form-group col-12 col-sm-10 col-lg-6">
                                                    <label for="">Nom</label>
                                                    <input type="text" id="nom" class="form-control p-3 border" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                                                </div>
                                                <div class="form-group col-12 col-sm-10 col-lg-6">
                                                    <label for="">Prenom</label>
                                                    <input type="text" id="prenom" class="form-control p-3" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                                                </div>
                                                <div class="form-group col-12 col-sm-10 col-lg-6">
                                                    <label for="">E-mail</label>
                                                    <input type="email" id="email" class="form-control p-3" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                                </div>
                                                <div>
                                                    <button type="submit" class="btn btn-primary">Modifier Informations</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <h4>Aide et Support</h4>
                                    <div class="col-6 col-sm-10 col-lg-6">
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        Politique de confidentialite
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        Introduction
                                                        Nous respectons votre vie privée et nous nous engageons à protéger vos données personnelles. Cette politique de confidentialité vous informe sur la manière dont nous collectons, utilisons, partageons et protégeons vos informations.

                                                        Collecte des Informations
                                                        Nous collectons des informations lorsque vous vous inscrivez sur notre site, passez une commande, vous abonnez à notre newsletter ou remplissez un formulaire. Les informations collectées peuvent inclure votre nom, adresse e-mail, numéro de téléphone, et d'autres détails pertinents.

                                                        Utilisation des Informations
                                                        Les informations que nous collectons peuvent être utilisées pour :

                                                        Personnaliser votre expérience
                                                        Améliorer notre site web
                                                        Améliorer le service client
                                                        Traiter les transactions
                                                        Envoyer des e-mails périodiques
                                                        Partage des Informations
                                                        Nous ne vendons, n'échangeons ni ne transférons vos informations personnelles à des tiers sans votre consentement, sauf si la loi nous y oblige.

                                                        Protection des Informations
                                                        Nous mettons en œuvre diverses mesures de sécurité pour protéger vos informations personnelles. Vos informations sensibles sont cryptées via la technologie SSL (Secure Socket Layer).

                                                        Consentement
                                                        En utilisant notre site, vous consentez à notre politique de confidentialité.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        FAQ
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        Comment créer un compte ?
                                                        Pour créer un compte, cliquez sur le bouton "S'inscrire" en haut à droite de la page d'accueil et remplissez le formulaire d'inscription.

                                                        J'ai oublié mon mot de passe. Que dois-je faire ?
                                                        Cliquez sur "Mot de passe oublié" sur la page de connexion, entrez votre adresse e-mail et suivez les instructions pour réinitialiser votre mot de passe.

                                                        Comment puis-je mettre à jour mes informations personnelles ?
                                                        Connectez-vous à votre compte, allez dans la section "Profil" et mettez à jour vos informations.

                                                        Comment contacter le support client ?
                                                        Vous pouvez nous contacter via la section "Aide et Support" de notre site web ou envoyer un e-mail à support@notresite.com.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        Preferences
                                                    </button>
                                                </h2>
                                                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        Notifications
                                                        Vous pouvez gérer vos préférences de notification dans la section "Paramètres" de votre profil. Choisissez les types de notifications que vous souhaitez recevoir (e-mail, SMS, push).

                                                        Langue
                                                        Notre application est disponible en plusieurs langues. Vous pouvez changer la langue de l'interface dans la section "Langue" des paramètres.

                                                        Confidentialité
                                                        Gérez vos paramètres de confidentialité et contrôlez quelles informations vous souhaitez partager dans la section "Confidentialité" de votre profil.

                                                        Thème
                                                        Personnalisez l'apparence de notre application en choisissant parmi différents thèmes disponibles dans la section "Thème" des paramètres.

                                                        Ces sections devraient couvrir les aspects principaux de la politique de confidentialité, FAQ, aide et support, et préférences de votre application. Assurez-vous de personnaliser ces exemples en fonction des fonctionnalités spécifiques et des politiques de votre application.







                                                    </div>
                                                </div>
                                            </div>
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
                                    <a href="./catalogue.php" class="sidebar-link text-decoration-none text-black">
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
                                    <a href="./profil.php" class="sidebar-link text-decoration-none active text-black ">
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
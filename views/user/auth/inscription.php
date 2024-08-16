<?php
session_start();
require_once '../../../includes/database.php';

// Récupérer les erreurs depuis la session
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Inscription</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="../../../assets/css/auth.css">
    <link href="
      https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.min.css
      " rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body class="d-flex justify-content-center" style="background-image: linear-gradient(to right bottom, #ffffff, #e3e4fe, #bfcbfe, #8eb5ff, #38a1ff);">
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div class="col-12 col-sm-10 col-lg-6">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <img src="../../../assets/img/logo.png" alt="logo" class="logo" />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="../../../controllers/user/inscription.php" class="bg-white p-3 shadow rounded mt-3" method="post">
                        <div class="row">
                            <div class="col-12">
                                <input type="text" name="prenom" class="form-control p-3" placeholder="Votre prenom" />
                                <?php if (!empty($errors["prenom"])) : ?>
                                    <li class="text-danger"><?= $errors['prenom'] ?></li>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <input type="text" name="nom" class="form-control p-3" placeholder="Votre nom" />
                                <?php if (!empty($errors["nom"])) : ?>
                                    <li class="text-danger"><?= $errors['nom'] ?></li>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <input type="email" name="email" class="form-control p-3" placeholder="Votre e-mail" />
                                <?php if (!empty($errors["email"])) : ?>
                                    <li class="text-danger"><?= $errors['email'] ?></li>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <input type="password" name="password" class="form-control p-3" placeholder="Mot de passe" />
                                <?php if (!empty($errors["password"])) : ?>
                                    <li class="text-danger"><?= $errors['password'] ?></li>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-inscription text-white w-100">
                                    Inscription
                                </button>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="m-0 text-center">Vous avez déja un compte ? <a href="./connexion.php" class="text-decoration-none connecte">Se connecter</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
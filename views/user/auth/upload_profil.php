<?php
session_start();

require_once '../../../includes/database.php';
require_once '../../../includes/function.php';
require_once '../../../includes/verified.php';

isUserAuthenticated();
$errors = isset($_SESSION["errors"]) ? $_SESSION["errors"] : [];
$success = isset($_SESSION["success"]) ? $_SESSION["success"] : "";
unset($_SESSION["success"]);


// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$user = getUserInfo($pdo, $user_id);
$profile_picture = $user['profil'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload profile</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="../../../assets/css/profils.css">
    <link href="
      https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.min.css
      " rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body class="d-flex justify-content-center">
    <div class="container d-flex flex-column justify-content-center align-items-center position-relative">
        <div class="col-12 col-sm-10 col-lg-6">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-center">
                    <div class="profile-container">
                        <img src="../../../uploads/profiles/<?= htmlspecialchars($profile_picture, ENT_QUOTES, 'UTF-8') ?>" class="profils" id="profilePicture" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="../../../controllers/user/upload_profil.php" class="bg-white p-3 shadow rounded mt-3" enctype="multipart/form-data" method="post">
                        <div class="row">
                            <div class="col-12">
                                <label for="picture" class="mb-3">Choisir votre photo de profil</label>
                                <input type="file" name="picture" class="form-control p-3" id="pictureInput" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn bg-primary text-white w-100">
                                    Validez
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if (!empty($success)) : ?>
            <?= notification("ri-checkbox-circle-fill", "success", $success); ?>
        <?php endif; ?>
    </div>
    <script src="../../../assets/javascript/profils.js"></script>

</body>

</html>
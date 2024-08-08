<?php
session_start();
require_once '../../../includes/database.php';

// Vérifier si l'ID du livre est passé en paramètre
$livreId = $_GET['id'] ?? null;

if (!$livreId) {
    header('Location: ./myBooks.php');
    exit();
}

// Récupérer les détails du livre
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = :id");
$stmt->execute([':id' => $livreId]);
$livre = $stmt->fetch();

if (!$livre) {
    header('Location: ./myBooks.php');
    exit();
}

// Récupérer les erreurs de session
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Editer livres</title>
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
            <div class="col-2 sidebar d-none d-lg-flex flex-column justify-content-center col-lg-2 bg-white p-0" style="height: 100vh; position: fixed; left: 0; bottom: 0">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <img src="../../../assets/img/logo.png" class="img-fluid d-none d-lg-block" style="height: 50px" alt="" />
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
                                <div class="">
                                    <p class="m-0 fw-bold">Administrateur</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-12 bg-white shadow-sm d-flex flex-column justify-content-between" style="min-height: 88vh;">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center">
                                        <h4 class="text-center mb-4">Publier un livre</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <form action="../../../controllers/admin/editsLivres.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($livre['id_livre']) ?>">
                                        <div class="col-12">
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-12 col-sm-6 form-group">
                                                    <input type="text" id="nom" name="nom" class="form-control p-3" value="<?= htmlspecialchars($livre['titre']) ?>">
                                                    <?php if (!empty($errors['nom'])) : ?>
                                                        <div class="text-danger"><?= $errors['nom'] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-12 col-sm-6 form-group">
                                                    <input type="text" id="auteur" name="auteur" class="form-control p-3" value="<?= htmlspecialchars($livre['auteur']) ?>">
                                                    <?php if (!empty($errors['nom_auteur'])) : ?>
                                                        <div class="text-danger"><?= $errors['nom_auteur'] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-between mt-3">
                                                <div class="col-12 col-sm-6 form-group">
                                                    <select id="categorie" name="categorie" class="form-select p-3">
                                                        <?php
                                                        $stmt = $pdo->query("SELECT * FROM categories");
                                                        while ($category = $stmt->fetch()) {
                                                            echo "<option value=\"{$category['id_categorie']}\"" . ($category['id_categorie'] == $livre['categorie'] ? ' selected' : '') . ">{$category['libelle']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($errors['categorie'])) : ?>
                                                        <div class="text-danger"><?= $errors['categorie'] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-12 col-sm-6 form-froup">
                                                    <input readonly type="text" id="isbn" name="isbn" class="form-control p-3" value="<?= htmlspecialchars($livre['ISBN']) ?>">
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-between mt-3">
                                                <div class="col-12 col-sm-6 form-group">
                                                    <select name="status" id="" class="form-control p-3">
                                                        <?php
                                                        $stmt2 = $pdo->query("SELECT * FROM status");
                                                        while ($statu = $stmt2->fetch()) {
                                                            echo "<option value=\"{$statu['id_status']}\"" . ($statu['id_status'] == $livre['status'] ? ' selected' : '') . ">{$statu['libelle']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($errors['status'])) : ?>
                                                        <div class="text-danger"><?= $errors['status'] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-12 col-sm-6 form-group">
                                                    <input type="file" id="couverture" name="couverture" class="form-control p-3">
                                                    <?php if (!empty($livre['couverture'])) : ?>
                                                        <img src="../../../uploads/couvertures/<?= htmlspecialchars($livre['couverture']) ?>" alt="Cover Image" class="img-thumbnail mt-2" width="100">
                                                    <?php endif; ?>
                                                    <?php if (!empty($errors['photo_couverture'])) : ?>
                                                        <div class="text-danger"><?= $errors['photo_couverture'] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-between mt-3">
                                                <div class="col-12 form-group">
                                                    <textarea id="description" name="description" class="form-control p-3" rows="5"><?= htmlspecialchars($livre['description']) ?></textarea>
                                                    <?php if (!empty($errors['description'])) : ?>
                                                        <div class="text-danger"><?= $errors['description'] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-between mt-3">
                                                <div class="col-12 col-sm-6">
                                                    <button type="submit" class="btn btn-primary w-100">Modifier</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
    <!-- Bootstrap JavaScript Libraries -->
    <script src="../../../assets/javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
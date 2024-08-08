<?php
session_start();
require_once '../../includes/database.php';

// Tableau pour stocker les erreurs de formulaire
$errors = [
    'nom' => '',
    'nom_auteur' => '',
    'categorie' => '',
    'isbn' => '',
    'status' => '',
    'photo_couverture' => '',
    'description' => ''
];

// Traitement du formulaire d'édition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $livreId = $_POST['id'] ?? null;
    $nom = trim($_POST['nom']);
    $nom_auteur = trim($_POST['auteur']);
    $categorie = trim($_POST['categorie']);
    $isbn = trim($_POST['isbn']);
    $status = trim($_POST['status']);
    $description = trim($_POST['description']);
    $photo_couverture = $_FILES['couverture'];

    // Taille maximale du fichier (5 Mo)
    $maxFileSize = 5 * 1024 * 1024;

    // Types de fichiers autorisés
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    // Validation des champs
    if (empty($nom)) {
        $errors['nom'] = "Le nom du livre est requis.";
    }
    if (empty($nom_auteur)) {
        $errors['nom_auteur'] = "Le nom de l'auteur est requis.";
    }
    if (empty($categorie)) {
        $errors['categorie'] = "La catégorie est requise.";
    }
    if (empty($status)) {
        $errors['status'] = "Le statut est requis.";
    }
    if (empty($description)) {
        $errors['description'] = "La description est requise.";
    }

    // Traitement de la photo de couverture (si modifiée)
    if (!empty($photo_couverture['name'])) {
        // Vérifier la taille du fichier
        if ($photo_couverture['size'] > $maxFileSize) {
            $errors['photo_couverture'] = "La taille de la photo de couverture ne doit pas dépasser 5 Mo.";
        }

        // Vérifier le type de fichier
        if (!in_array($photo_couverture['type'], $allowedFileTypes)) {
            $errors['photo_couverture'] = "La photo de couverture doit être au format JPG, JPEG ou PNG.";
        }

        // Gérer le téléchargement de la photo de couverture
        $target_dir = "../../uploads/couvertures/";
        $target_file = $target_dir . basename($photo_couverture["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!move_uploaded_file($photo_couverture["tmp_name"], $target_file)) {
            $errors['photo_couverture'] = "Erreur lors du téléchargement de la photo de couverture.";
        }
    }

    if (empty(array_filter($errors))) {
        // Mise à jour du livre dans la base de données
        $stmt = $pdo->prepare("UPDATE livres SET titre = :nom, auteur = :nom_auteur, categorie = :categorie, status = :status, description = :description" . (!empty($photo_couverture['name']) ? ", couverture = :photo_couverture" : "") . " WHERE id_livre = :id");
        $params = [
            ':nom' => $nom,
            ':nom_auteur' => $nom_auteur,
            ':categorie' => $categorie,
            ':status' => $status,
            ':description' => $description,
            ':id' => $livreId
        ];

        if (!empty($photo_couverture['name'])) {
            $params[':photo_couverture'] = basename($photo_couverture["name"]);
        }

        $stmt->execute($params);

        $_SESSION["success"] = "Livre mis à jour avec succès!";
        header('Location: ../../views/admin/pages/myBooks.php');
        exit();
    }

    // Stocker les erreurs en session si présentes
    $_SESSION['errors'] = $errors;
    header('Location: ../../views/admin/pages/editLivre.php?id=' . $livreId);
}

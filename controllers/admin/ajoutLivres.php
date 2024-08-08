<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

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

// Taille maximale du fichier (5 Mo)
$maxFileSize = 5 * 1024 * 1024;

// Types de fichiers autorisés
$allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

// Traitement du formulaire d'ajout de livre
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $nom_auteur = trim($_POST['auteur']);
    $categorie = trim($_POST['categorie']);
    $isbn = trim($_POST['isbn']);
    $status = trim($_POST['status']);
    $description = trim($_POST['description']);
    $photo_couverture = $_FILES['couverture'];

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
    if (empty($isbn)) {
        $errors['isbn'] = "L'ISBN est requis.";
    } else {
        // Vérifier si l'ISBN existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM livres WHERE ISBN = :isbn");
        $stmt->execute(['isbn' => $isbn]);
        $isbnExists = $stmt->fetchColumn();

        if ($isbnExists) {
            $errors['isbn'] = "L'ISBN existe déjà.";
        }
    }
    if (empty($status)) {
        $errors['status'] = "Le statut est requis.";
    }
    if (empty($description)) {
        $errors['description'] = "La description est requise.";
    }
    if (empty($photo_couverture['name'])) {
        $errors['photo_couverture'] = "La photo de couverture est requise.";
    } else {
        // Vérifier la taille du fichier
        if ($photo_couverture['size'] > $maxFileSize) {
            $errors['photo_couverture'] = "La taille de la photo de couverture ne doit pas dépasser 5 Mo.";
        }

        // Vérifier le type de fichier
        if (!in_array($photo_couverture['type'], $allowedFileTypes)) {
            $errors['photo_couverture'] = "La photo de couverture doit être au format JPG, JPEG ou PNG.";
        }
    }

    if (empty(array_filter($errors))) {
        // Gérer le téléchargement de la photo de couverture
        $target_dir = "../../uploads/couvertures/";
        $target_file = $target_dir . basename($photo_couverture["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est une image réelle
        if (move_uploaded_file($photo_couverture["tmp_name"], $target_file)) {
            // Insérer le livre dans la base de données
            $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, categorie, ISBN, status, couverture, description) VALUES (:nom, :nom_auteur, :categorie, :isbn, :status, :photo_couverture, :description)");
            $stmt->execute([
                ':nom' => $nom,
                ':nom_auteur' => $nom_auteur,
                ':categorie' => $categorie,
                ':isbn' => $isbn,
                ':status' => $status,
                ':photo_couverture' => basename($photo_couverture["name"]),
                ':description' => $description
            ]);

            $_SESSION["success"] = "Livre ajouté avec succès!";
            header('Location: ../../views/admin/pages/addLivre.php');
            exit();
        } else {
            $errors['photo_couverture'] = "Erreur lors du téléchargement de la photo de couverture.";
            error_log('Erreur de téléchargement de fichier : ' . $_FILES['couverture']['error']); // Ajout d'un message d'erreur dans le log
        }
    }

    // Stocker les erreurs en session si présentes
    $_SESSION['errors'] = $errors;
    header('Location: ../../views/admin/pages/addLivre.php');
}

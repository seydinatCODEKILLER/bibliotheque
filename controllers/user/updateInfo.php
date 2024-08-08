<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/user/pages/connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    // Validation des champs
    if (empty($nom) || empty($prenom) || empty($email)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header('Location: ../../views/user/pages/profil.php');
        exit;
    }

    // Mise à jour des informations de l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET nom = :nom, prenom = :prenom, email = :email WHERE id_user = :id_user");
    $result = $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':id_user' => $user_id,
    ]);

    if ($result) {
        $_SESSION['success'] = "Informations mises à jour avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour des informations.";
    }

    header('Location: ../../views/user/pages/profil.php');
    exit;
}

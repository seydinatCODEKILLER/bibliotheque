<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

// Tableau pour stocker les erreurs de formulaire
$errors = [
    'email' => '',
    'password' => ''
];

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation des champs
    if (empty($email)) {
        $errors['email'] = "L'email est requis.";
    }
    if (empty($password)) {
        $errors['password'] = 'Le mot de passe est requis.';
    }

    if (empty(array_filter($errors))) {
        // Authentification de l'utilisateur
        $result = authentification($email, $password);
        if ($result['authenticated']) {
            $_SESSION['user_id'] = $result['user']['id_user'];
            $_SESSION["success"] = "Connexion réussie !";
            // Effacer les erreurs en session avant la redirection
            unset($_SESSION['errors']);
            header('Location: ../../index.php');
            exit();
        } else {
            if ($result['user']) {
                $errors['password'] = 'Mot de passe incorrect.';
            } else {
                $errors['email'] = 'Identifiant incorrect.';
            }
        }
    }

    // Stocker les erreurs en session si présentes
    $_SESSION['errors'] = $errors;
    header('Location: ../../views/user/auth/connexion.php');
    exit();
}

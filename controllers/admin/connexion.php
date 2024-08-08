<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

$errors = [
    'email' => '',
    'password' => ''
];

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
        // Authentification de l'administrateur
        $result = authentificationAdmin($email, $password);
        if ($result['authenticated']) {
            $_SESSION['admin_id'] = $result['admin']['id_admin'];
            $_SESSION["success"] = "Connexion réussie !";
            // Effacer les erreurs en session avant la redirection
            unset($_SESSION['errors']);
            header('Location: ../../admin_dashboard.php');
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
    header('Location: ../../views/admin/auth/connexion.php');
    exit();
}

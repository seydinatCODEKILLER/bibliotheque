<?php
session_start();
require_once "../../includes/database.php";
require_once "../../includes/function.php";

$errors = [
    'prenom' => '',
    'nom' => '',
    'email' => '',
    'password' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = trim($_POST["prenom"]);
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $created_at = date('Y-m-d H:i:s');

    // Validation des champs
    $errors = validation_champ($prenom, $nom, $email, $password);
    if (empty(array_filter($errors))) {
        $profile_picture = "avatar.png";

        if (register_user($prenom, $nom, $email, $password, $profile_picture, $created_at)) {
            // Obtenir l'ID de l'utilisateur nouvellement créé
            $_SESSION["user_id"] = $pdo->lastInsertId();
            $_SESSION["username"] = $prenom;
            $_SESSION["success"] = "Vos données ont été enregister avec success !";

            $_SESSION["errors"] = [];
            redirectUrl("../../views/user/auth/upload_profil.php");
        } else {
            $errors["email"] = "L'email est déjà utilisé.";
        }
    }

    $_SESSION["errors"] = $errors;
    redirectUrl("../../views/user/auth/inscription.php");
}

<?php

function isUserAuthenticated()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: connexion.php");
        exit();
    }
}

function getUserInfo($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_emprunt = $_POST['id_emprunt'];

    // Vérifier si l'emprunt existe
    $stmt = $pdo->prepare("SELECT * FROM emprunts WHERE id_emprunt = :id_emprunt");
    $stmt->execute([':id_emprunt' => $id_emprunt]);
    $emprunt = $stmt->fetch();

    if ($emprunt) {
        // Mettre à jour le statut de l'emprunt à annulé
        $stmt = $pdo->prepare("UPDATE emprunts SET status = 'annulé' WHERE id_emprunt = :id_emprunt");
        $stmt->execute([':id_emprunt' => $id_emprunt]);

        // Mettre à jour le statut du livre à disponible
        $stmt = $pdo->prepare("UPDATE livres SET status = 1 WHERE id_livre = :id_livre");
        $stmt->execute([':id_livre' => $emprunt['id_livre']]);

        // Rediriger vers la page des emprunts avec un message de succès
        $_SESSION['message'] = "L'emprunt a été annulé avec succès.";
    } else {
        // Rediriger vers la page des emprunts avec un message d'erreur
        $_SESSION['error'] = "L'emprunt n'existe pas.";
    }
}

header('Location: ../../views/admin/pages/emprunt.php');
exit;

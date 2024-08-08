<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Mettre à jour le statut de l'abonné à "suspendu"
    $stmt = $pdo->prepare("UPDATE users SET status = 'suspendu' WHERE id_user = :id");
    $stmt->execute([':id' => $id]);

    // Ajouter une notification pour l'utilisateur
    $stmt2 = $pdo->prepare("INSERT INTO notifications (id_user, message, date) VALUES (:id_user, :message, NOW())");
    $stmt2->execute([
        'id_user' => $id,
        'message' => "Vous avez été suspendu par l'administrateur.",
    ]);

    $_SESSION['success'] = "Abonné suspendu avec succès.";
    header('Location: ../../views/admin/pages/abonnee.php');
}

<?php
require_once "../../includes/database.php";
if (isset($_GET['delete_id'])) {
    $notification_id = $_GET['delete_id'];

    // Supprimer la notification de la base de données
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE id_notification = :id");
    $stmt->execute([':id' => $notification_id]);

    // Redirection pour actualiser la page
    header('Location: ../../views/user/pages/notification.php');
    exit();
}

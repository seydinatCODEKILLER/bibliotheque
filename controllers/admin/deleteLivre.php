<?php
session_start();
require_once '../../includes/database.php';

// Vérifier si l'ID du livre est passé en paramètre
$livreId = $_GET['id'] ?? null;

if ($livreId) {
    // Récupérer les détails du livre pour supprimer la couverture
    $stmt = $pdo->prepare("SELECT couverture FROM livres WHERE id_livre = :id");
    $stmt->execute([':id' => $livreId]);
    $livre = $stmt->fetch();

    if ($livre) {
        // Supprimer la couverture du fichier
        $target_dir = "../../../uploads/couvertures/";
        $photo_couverture = $livre['couverture'];
        if ($photo_couverture) {
            unlink($target_dir . $photo_couverture);
        }

        // Supprimer le livre de la base de données
        $stmt = $pdo->prepare("DELETE FROM livres WHERE id_livre = :id");
        $stmt->execute(['id' => $livreId]);

        $_SESSION["success"] = "Livre supprimé avec succès!";
    } else {
        $_SESSION["error"] = "Livre non trouvé.";
    }
} else {
    $_SESSION["error"] = "ID du livre manquant.";
}

header('Location: ../../views/admin/pages/myBooks.php');
exit();

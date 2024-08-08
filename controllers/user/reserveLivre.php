<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['user_id'];
    $id_livre = $_POST['id_livre'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if (empty($date_debut) || empty($date_fin)) {
        $_SESSION['error'] = "Veuillez renseigner les dates de début et de fin pour la réservation.";
        header('Location: ../../views/user/pages/catalogue.php');
        exit();
    }

    // Vérifier si l'utilisateur est suspendu
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id_user = :id_user");
    $stmt->execute([':id_user' => $id_user]);
    $userStatus = $stmt->fetchColumn();
    if ($userStatus == 'suspendu') {
        $_SESSION['error'] = "Vous êtes suspendu et ne pouvez pas emprunter de livres.";
        header('Location: ../../views/user/pages/catalogue.php');
        exit();
    }

    // Vérification des emprunts actifs de l'utilisateur
    $stmt = $pdo->prepare("SELECT e.*, l.categorie FROM emprunts e JOIN livres l ON e.id_livre = l.id_livre WHERE e.id_user = :id_user AND e.status != 'terminé'");
    $stmt->execute([':id_user' => $id_user]);
    $activeLoans = $stmt->fetchAll();

    foreach ($activeLoans as $loan) {
        if ($loan['status'] == 'échu') {
            $stmt = $pdo->prepare("UPDATE users SET status = 'suspendu' WHERE id_user = :id_user");
            $stmt->execute([':id_user' => $id_user]);
            $_SESSION['error'] = "Vous avez des emprunts échus. Vous êtes suspendu.";
            header('Location: ../../views/user/pages/catalogue.php');
            exit();
        }
    }

    // Vérifier les emprunts actifs dans la même catégorie
    $stmt = $pdo->prepare("SELECT l.categorie FROM emprunts e JOIN livres l ON e.id_livre = l.id_livre WHERE e.id_user = :id_user AND e.status = 'en cours'");
    $stmt->execute([':id_user' => $id_user]);
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $pdo->prepare("SELECT categorie FROM livres WHERE id_livre = :id_livre");
    $stmt->execute([':id_livre' => $id_livre]);
    $categorieLivre = $stmt->fetchColumn();

    if (in_array($categorieLivre, $categories)) {
        $_SESSION['error'] = "Vous avez déjà un emprunt en cours dans la catégorie " . $categorieLivre . ".";
        header('Location: ../../views/user/pages/catalogue.php');
        exit();
    }

    // Vérifier si le livre est disponible
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = :id_livre AND status = 1");
    $stmt->execute([':id_livre' => $id_livre]);
    if ($stmt->rowCount() == 0) {
        $_SESSION['error'] = "Ce livre n'est pas disponible pour le moment.";
        header('Location: ../../views/user/pages/catalogue.php');
        exit();
    }

    // Insérer la réservation
    $stmt = $pdo->prepare("INSERT INTO emprunts (id_user, id_livre, date_debut, date_fin, retourner, status) VALUES (:id_user, :id_livre, :date_debut, :date_fin, 0, 'en cours')");
    $stmt->execute([
        ':id_user' => $id_user,
        ':id_livre' => $id_livre,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
    ]);

    // Mettre à jour le statut du livre à "Emprunté"
    $stmt = $pdo->prepare("UPDATE livres SET status = 0 WHERE id_livre = :id_livre");
    $stmt->execute([':id_livre' => $id_livre]);

    $_SESSION['success'] = "Réservation effectuée avec succès!";
    header('Location: ../../views/user/pages/catalogue.php');
    exit();
}

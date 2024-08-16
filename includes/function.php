<?php

function redirectUrl($url)
{
    header("Location: $url");
    exit();
}

function validate_unique_email($email)
{
    global $pdo;
    $requete = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($requete);
    $stmt->execute([
        'email' => $email
    ]);
    return $stmt->rowCount() == 0;
}

function register_user($prenom, $nom, $email, $password, $profile_picture, $created_at)
{
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, profil, createdAt) VALUES (:prenom, :nom, :email, :password, :profile_picture, :created_at)");
    return $stmt->execute([
        ':prenom' => $prenom,
        ':nom' => $nom,
        ':email' => $email,
        ':password' => $hashed_password,
        ':profile_picture' => $profile_picture,
        ':created_at' => $created_at
    ]);
}

function authentification($email, $password)
{
    global $pdo;
    $requete = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($requete);
    $stmt->execute([
        'email' => $email
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (password_verify($password, $user['password'])) {
            return ['authenticated' => true, 'user' => $user];
        } else {
            return ['authenticated' => false, 'user' => $user];
        }
    } else {
        return ['authenticated' => false, 'user' => null];
    }
    return false;
}

function validation_champ($prenom, $nom, $email, $password)
{
    $errors = [
        'prenom' => '',
        'nom' => '',
        'email' => '',
        'password' => ''
    ];

    if (empty($prenom)) {
        $errors['prenom'] = 'Veuillez entrer votre prénom.';
    }

    if (empty($nom)) {
        $errors['nom'] = 'Veuillez entrer votre nom.';
    }

    if (empty($email)) {
        $errors['email'] = 'Veuillez entrer votre adresse email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Adresse email invalide.';
    } else {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Cette adresse email est déjà utilisée.';
        }
    }

    if (empty($password)) {
        $errors['password'] = 'Veuillez entrer votre mot de passe.';
    }

    return $errors;
}

function notification($icon, $type, $message)
{
    echo "
    <div class='col-5 p-3 d-flex gap-3 align-items-center shadow-sm bg-white z-3 position-absolute top-0 start-50 translate-middle-x notif'>
        <i class='$icon text-$type fs-4'></i>
        <p class='m-0'>$message</p>
        <div class='position-absolute top-0 end-0 p-1'>
            <i class='ri-close-circle-fill text-$type fs-4 close'></i>
        </div>
    </div>
    ";
}

function authentificationAdmin($email, $password)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            return ['authenticated' => true, 'admin' => $user];
        } else {
            return ['authenticated' => false, 'admin' => $user];
        }
    } else {
        return ['authenticated' => false, 'admin' => null];
    }
}

function getCategories($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsers($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStatus($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM status");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLivres($pdo, $categorie_id = null)
{
    if ($categorie_id) {
        $stmt = $pdo->prepare("SELECT * FROM livres WHERE categorie = :categorie_id");
        $stmt->execute([':categorie_id' => $categorie_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM livres");
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getThreeFirstBooks($pdo)
{
    $stmt = $pdo->query("SELECT * FROM livres ORDER BY id_livre LIMIT 3");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllUsers($pdo, $statusFilter)
{
    $query = "SELECT * FROM users";
    if ($statusFilter !== 'all') {
        $query .= " WHERE status = :status";
    }
    $stmt = $pdo->prepare($query);

    if ($statusFilter !== 'all') {
        $stmt->execute([':status' => $statusFilter]);
    } else {
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllNotificationsUsers($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT id_notification, message, date FROM notifications WHERE id_user = :id_user ORDER BY date DESC");
    $stmt->execute([':id_user' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $notifications;
}

function getUserWithId($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCountNotif($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE id_user = :id_user");
    $stmt->execute([':id_user' => $user_id]);
    $notification_count = $stmt->fetchColumn();
    return $notification_count;
}

function searchParamsToGetBooks($pdo, $filters)
{
    $searchTerm = $filters['search'] ?? '';
    $selectedCategory = $filters['category'] ?? '';

    $query = "SELECT * FROM livres WHERE 1";
    $params = [];

    if (!empty($searchTerm)) {
        $query .= " AND titre LIKE :searchTerm";
        $params[':searchTerm'] = '%' . $searchTerm . '%';
    }

    if (!empty($selectedCategory)) {
        $query .= " AND categorie = :selectedCategory";
        $params[':selectedCategory'] = $selectedCategory;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEmprunts($pdo, $selected_status)
{
    // Préparer la requête en fonction du statut sélectionné
    $query = "SELECT e.*, l.titre, l.auteur, u.nom, u.prenom FROM emprunts e 
    JOIN livres l ON e.id_livre = l.id_livre 
    JOIN users u ON e.id_user = u.id_user";

    if (!empty($selected_status)) {
        $query .= " WHERE e.status = :status";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':status' => $selected_status]);
    } else {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    return $stmt->fetchAll();
}

function getEmpruntsUser($pdo, $selected_status, $user_id)
{
    // Préparer la requête pour sélectionner les emprunts de l'utilisateur connecté
    $query = "SELECT e.*, l.titre, l.auteur, u.nom, u.prenom FROM emprunts e 
    JOIN livres l ON e.id_livre = l.id_livre 
    JOIN users u ON e.id_user = u.id_user 
    WHERE e.id_user = :user_id";

    // Ajouter une condition supplémentaire si un statut est sélectionné
    if (!empty($selected_status)) {
        $query .= " AND e.status = :status";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $user_id, ':status' => $selected_status]);
    } else {
        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
    }

    return $stmt->fetchAll();
}

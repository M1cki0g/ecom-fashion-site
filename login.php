<?php
// Démarrer la session pour gérer l'utilisateur connecté
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données saisies dans le formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier que les champs ne sont pas vides
    if (empty($email) || empty($password)) {
        die("Veuillez remplir tous les champs.");
    }

    // Connexion à la base de données
    $host = 'localhost'; // Hôte
    $dbname = 'ecommerce_db'; // Nom de la base
    $db_user = 'root'; // Utilisateur de la base
    $db_password = ''; // Mot de passe de la base

    try {
        // Création de la connexion avec PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Rechercher l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        // Stocker l'utilisateur dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // Rediriger vers la page principale (dashboard ou autre)
        header("Location: home.html");
        exit();
    } else {
        // Afficher un message d'erreur en cas d'échec
        echo "Email ou mot de passe incorrect.";
    }
} else {
    // Rediriger si la page est visitée sans soumettre le formulaire
    header("Location: logIN.html");
    exit();
}

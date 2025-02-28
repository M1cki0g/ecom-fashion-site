<?php
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If the email or username already exists, throw an exception
    if (count($users) > 0) {
        throw new Exception("Email or username already exists");
    }

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->execute([$email, $username, $pwd]);

    // Fetch the newly added user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND username = ?");
    $stmt->execute([$email, $username]);
    $newUser = $stmt->fetch(PDO::FETCH_ASSOC);

    // Redirect to the home page after successful sign-in
    header("Location: /home.php");
    exit();

    // Return a success message with user details
    return [
        "success" => "User added successfully",
        "user" => $newUser
    ];
} catch (Exception $e) {
    // Return an error message
    return ["error" => $e->getMessage()];
}

<?php
require 'db.php'; // Inclure la connexion à la base de données
require 'header.php'; // Inclure l'en-tête

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_id = $_POST['role_id'];

    // Vérifier si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        echo "<p style='color: red;'>Le nom d'utilisateur existe déjà. Veuillez en choisir un autre.</p>";
    } else {
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare('INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)');
            $stmt->execute([$username, $hashed_password, $role_id]);

            echo "<p style='color: green;'>Inscription réussie!</p>";
        } else {
            echo "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form method="POST">
            Nom d'utilisateur: <input type="text" name="username" required>
            Mot de passe: <input type="password" name="password" required>
            Confirmer le mot de passe: <input type="password" name="confirm_password" required>
            Rôle: 
            <select name="role_id" required>
                <option value="1">Enfant</option>
                <option value="2">Parent</option>
                <option value="3">Enseignant</option>
            </select>
            <button type="submit">S'inscrire</button>
        </form>
        <div class="link">
            <p>Vous avez déjà un compte ? <a href="login.php">Connexion</a></p>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
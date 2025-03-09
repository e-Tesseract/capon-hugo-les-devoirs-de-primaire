<?php
require 'header.php'; // Inclure l'en-tête
require 'db.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        header("Location: index.php"); // Rediriger vers la page d'accueil
        exit();
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect!";
    }
}
?>
<form method="POST">
    Nom d'utilisateur: <input type="text" name="username" required>
    Mot de passe: <input type="password" name="password" required>
    <button type="submit">Se connecter</button>
</form>
<p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>

<?php include 'footer.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Les Devoirs de Primaire</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #45a1ff;
            padding: 10px 20px;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-left {
            flex: 1;
        }
        .nav-center {
            flex: 1;
            text-align: center;
        }
        .nav-right {
            flex: 1;
            text-align: right;
        }
        .nav-right a {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="nav-left">
            <a href="/index.php">Accueil</a>
        </div>
        <div class="nav-center">
            <?php if (isset($_SESSION['username'])): ?>
                <p>Bonjour, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <?php endif; ?>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="/profile.php">Profil</a>
                <a href="/logout.php">Se déconnecter</a>
            <?php elseif ($current_page !== 'login.php' && $current_page !== 'register.php'): ?>
                <a href="/login.php">Se connecter</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<?php
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "les_devoirs_de_primaire";

try {
    // Connexion au serveur MySQL
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Drop la base de données si elle existe
    $pdo->exec("DROP DATABASE IF EXISTS $dbname");
    echo "Database dropped successfully<br>";

    // Création de la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "Database created successfully<br>";

    // Connexion à la base de données
    $pdo->exec("USE $dbname");

    // Création des tables si elles n'existent pas

    // Création de la table roles
    $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL UNIQUE
    )");

    // Création de la table users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role_id INT,
        FOREIGN KEY (role_id) REFERENCES roles(id)
    )");

    // Création de la table exercices
    $pdo->exec("CREATE TABLE IF NOT EXISTS exercices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        type_exercice VARCHAR(50) NOT NULL,
        question TEXT NOT NULL,
        reponse_attendue TEXT NOT NULL,
        reponse_utilisateur TEXT NOT NULL,
        est_correct BOOLEAN NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Création de la table links pour lier les parents/profs et les enfants
    $pdo->exec("CREATE TABLE IF NOT EXISTS links (
        id INT AUTO_INCREMENT PRIMARY KEY,
        parent_prof_id INT,
        enfant_id INT,
        FOREIGN KEY (parent_prof_id) REFERENCES users(id),
        FOREIGN KEY (enfant_id) REFERENCES users(id)
    )");

    echo "Tables created successfully<br>";

    // Insertion des rôles
    $pdo->exec("INSERT INTO roles (role_name) VALUES ('Enfant')");
    $pdo->exec("INSERT INTO roles (role_name) VALUES ('Parent')");
    $pdo->exec("INSERT INTO roles (role_name) VALUES ('Enseignant')");
    
    echo "Roles inserted successfully<br>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>

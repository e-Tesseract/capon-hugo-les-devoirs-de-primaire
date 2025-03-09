# Documentation du Projet

## 1. Présentation du Projet
Ce projet est une plateforme web éducative permettant aux enfants de réaliser des exercices interactifs de conjugaison, calcul et dictée. Les parents et enseignants peuvent suivre leurs progrès et lier des enfants à leurs comptes.

---

## 2. Installation et Configuration

### Prérequis
- Serveur MAMP, WAMP, XAMPP ou autre.
- Navigateur Web (Chrome, Firefox, Safari, Edge, etc.).

### Etapes d'Installation
1. **Télécharger le projet**
    ```sh
    git clone https://github.com/e-Tesseract/capon-hugo-les-devoirs-de-primaire.git
    ```

2. **Configurer la base de données**
    - Ouvrir `create_database.php` et `db.php` et vérifier les informations de connexion :
      ```php
      $servername = "localhost:3306";
      $username = "root";
      $password = "root";
      $dbname = "les_devoirs_de_primaire";
      ```

3. **Lancer le serveur**
    - Via Mamp, Wamp, ou autre serveur local.
    - Accéder au projet via : `http://localhost`

4. **Créer la base de données**
    - Ouvrir `http://localhost/create_database.php` pour initialiser la base de données.

---

## 3. Manuel Utilisateur

### Connexion et Inscription
- **S’inscrire** : Choisir un nom d’utilisateur et un mot de passe.
- **Se connecter** : Entrer ses identifiants pour accéder aux exercices.

### Rôles
- **Enfant** : Fait les exercices.
- **Parent / Enseignant** : Suit la progression et lie des enfants. Peut aussi faire des exercices.

### Faire un Exercice
1. Sélectionner un type d’exercice (addition, soustraction, conjugaison…).
2. Répondre aux questions affichées.
3. Recommencer si nécessaire, ou appuyer sur « Accueil » pour revenir à la page d’accueil.

---

## 4. Manuel du Développeur

### Structure des Fichiers
```
les-devoirs-de-primaire/
├── addition/
│   ├── affiche_resultat.php
│   ├── correction.php
│   ├── fin.php
│   ├── index.php
│   ├── question.php
│   ├── raz.php
│   ├── supprimer_resultat.php
│   ├── utils.php
│   ├── logs/
│   ├── resultats/
│   └── supprime/
├── conjugaison_phrase/
│   ├── affiche_resultat.php
│   ├── correction.php
│   ├── fin.php
│   ├── index.php
│   ├── listeQuestions.txt
│   ├── question.php
│   ├── raz.php
│   ├── supprimer_resultat.php
│   ├── utils.php
│   ├── logs/
│   ├── resultats/
│   ├── verbes/
│   └── supprime/
├── conjugaison_verbe/
│   ├── affiche_resultat.php
│   ├── correction.php
│   ├── fin.php
│   ├── index.php
│   ├── question.php
│   ├── raz.php
│   ├── supprimer_resultat.php
│   ├── utils.php
│   ├── logs/
│   ├── resultats/
│   ├── verbes/
│   └── supprime/
├── dictee/
│   ├── affiche_resultat.php
│   ├── correction.php
│   ├── fin.php
│   ├── index.php
│   ├── question.php
│   ├── raz.php
│   ├── recopie.php
│   ├── supprimer_resultat.php
│   ├── utils.php
│   ├── logs/
│   ├── listeDeMots/
│   ├── sons/
│   ├── resultats/
│   └── supprime/
├── multiplication/
│   ├── affiche_resultat.php
│   ├── correction.php
│   ├── fin.php
│   ├── index.php
│   ├── question.php
│   ├── raz.php
│   ├── supprimer_resultat.php
│   ├── utils.php
│   ├── logs/
│   ├── resultats/
│   └── supprime/
├── soustraction/
│   ├── affiche_resultat.php
│   ├── correction.php
│   ├── fin.php
│   ├── index.php
│   ├── question.php
│   ├── raz.php
│   ├── supprimer_resultat.php
│   ├── utils.php
│   ├── logs/
│   ├── resultats/
│   └── supprime/
├── images/
├── create_database.php
├── db.php
├── footer.php
├── header.php
├── index.php
├── layout.php
├── LICENSE
├── login.php
├── logout.php
├── profile.php
├── README.md
└── register.php
```


## 5. Améliorations réalisées

### 1. Création d'un header et d'un footer
- Création de fichiers `header.php` et `footer.php` pour inclure le header et le footer dans toutes les pages, et suppression des balises `<footer>` dans les pages.

### 2. Création d'une base de données
- Création des tables `users`, `exercices`, `roles` et `links`.
- Création d'un script `create_database.php` pour initialiser la base de données.
- Modification des fichiers afin d'inserer les données dans la base de données. (ex: resultats des exercices)

### 3. Création d'un système de connexion et d'inscription
- Création des pages `login.php` et `register.php` pour se connecter et s'inscrire.

### 4. Création d'un système de déconnexion
- Création de la page `logout.php` pour se déconnecter.

### 5. Création d'une page de profil utilisateur
- Création de la page `profile.php` pour afficher les informations de l'utilisateur connecté.
- Affichage des derniers resultats de l'utilisateur.

### 6. Création de liens entre les utilisateurs
- Création de roles `Enfant`, `Parent` et `Enseignant`.
- Création de la table `links` pour lier les enfants aux parents/enseignants.
- Affichage des resultats des enfants liés aux parents/enseignants sur la page de profil.

### 7. Amélioration du système de logs
- Format amélioré [DATE] - IP: X.X.X.X - User: ID - Page: YYY.
- Meilleure lisibilité et structuration

### 8. Lien vers les images
- Changement des liens des images pour mettre un seul dossier `images/` à la racine du projet.
- Suppression des dossiers `images/` dans chaque exercice.

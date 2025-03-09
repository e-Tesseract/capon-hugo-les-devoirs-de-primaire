<?php
@ob_start();
session_start();
require 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur depuis la base de données
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT u.username, u.role_id, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}

$nom = $user['username'];
$role = $user['role_id'];

// Récupérer les dernières notes de l'utilisateur
$stmt = $pdo->prepare('SELECT type_exercice, question, reponse_attendue, reponse_utilisateur, est_correct, date FROM exercices WHERE user_id = ? ORDER BY date DESC');
$stmt->execute([$user_id]);
$notes = $stmt->fetchAll();

include 'header.php';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Profil</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .content {
            margin-top: 60px; 
            padding: 20px;
        }
        .scrollable-table {
            height: 400px;
            overflow-y: scroll;
            display: block;
        }
        .scrollable-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .scrollable-table th, .scrollable-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .scrollable-table th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .correct {
            background-color: #d4edda; 
        }
        .incorrect {
            background-color: #f8d7da; 
        }
    </style>
</head>
<body style="background-color:grey;">
    <div class="content">
        <center>
            <h1>Profil de <?php echo $nom; ?></h1>
            <p>Role : <?php echo $user['role_name']; ?></p>

            <!-- Si c'est un parent ou un prof, afficher le formulaire pour lier un enfant -->
            <?php 
            if ($role === 2 || $role === 3) { 
            ?>
                <h2>Lier un enfant</h2>

                <form method="post" action="profile.php">
                    <label for="search">Rechercher un enfant :</label>
                    <input type="text" id="search" name="search" placeholder="Nom d'utilisateur">
                    <button type="submit">Rechercher</button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
                    $search = $_POST['search'];
                    $stmt = $pdo->prepare('SELECT id, username FROM users WHERE username LIKE ? AND role_id = 1');
                    $stmt->execute(['%' . $search . '%']);
                    $children = $stmt->fetchAll();

                    if ($children) {
                        echo "<h3>Résultats de la recherche :</h3>";
                        echo "<ul>";
                        foreach ($children as $child) {
                            echo "<li>" . htmlspecialchars($child['username']) . " <form method='post' action='profile.php' style='display:inline;'><input type='hidden' name='enfant_id' value='" . $child['id'] . "'><button type='submit'>Lier</button></form></li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Aucun enfant trouvé.</p>";
                    }
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enfant_id'])) {
                    $enfant_id = $_POST['enfant_id'];

                    // Vérifier si l'enfant est déjà lié
                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM links WHERE parent_prof_id = ? AND enfant_id = ?');
                    $stmt->execute([$user_id, $enfant_id]);
                    $count = $stmt->fetchColumn();

                    if ($count == 0) {
                        // Insérer la relation dans la table links
                        $stmt = $pdo->prepare('INSERT INTO links (parent_prof_id, enfant_id) VALUES (?, ?)');
                        $stmt->execute([$user_id, $enfant_id]);
                        echo "<p>Enfant lié avec succès.</p>";
                    } else {
                        echo "<p>Enfant déjà lié.</p>";
                    }
                }

                // Délier un enfant
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delier_enfant_id'])) {
                    $delier_enfant_id = $_POST['delier_enfant_id'];
                    $stmt = $pdo->prepare('DELETE FROM links WHERE parent_prof_id = ? AND enfant_id = ?');
                    $stmt->execute([$user_id, $delier_enfant_id]);
                    echo "<p>Enfant délié avec succès.</p>";
                }

                // Récupérer les enfants liés
                $stmt = $pdo->prepare('SELECT u.id, u.username FROM users u JOIN links l ON u.id = l.enfant_id WHERE l.parent_prof_id = ?');
                $stmt->execute([$user_id]);
                $linked_children = $stmt->fetchAll();

                // Afficher les resultats pour chaque enfant lié
                foreach ($linked_children as $child) {
                    echo "<h2>Résultats de " . htmlspecialchars($child['username']) . " <form method='post' action='profile.php' style='display:inline;'><input type='hidden' name='delier_enfant_id' value='" . $child['id'] . "'><button type='submit'>❌</button></form></h2>";
                    $stmt = $pdo->prepare('SELECT e.type_exercice, e.question, e.reponse_attendue, e.reponse_utilisateur, e.est_correct, e.date FROM exercices e WHERE e.user_id = ? ORDER BY e.date DESC');
                    $stmt->execute([$child['id']]);
                    $child_notes = $stmt->fetchAll();

                    if ($child_notes) {
                        echo '<div class="scrollable-table">';
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Type d\'exercice</th>';
                        echo '<th>Question</th>';
                        echo '<th>Réponse attendue</th>';
                        echo '<th>Réponse utilisateur</th>';
                        echo '<th>Correct</th>';
                        echo '<th>Date</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach ($child_notes as $note) {
                            echo '<tr class="' . ($note['est_correct'] ? 'correct' : 'incorrect') . '">';
                            echo '<td>' . htmlspecialchars($note['type_exercice']) . '</td>';
                            echo '<td>' . htmlspecialchars($note['question']) . '</td>';
                            echo '<td>' . htmlspecialchars($note['reponse_attendue']) . '</td>';
                            echo '<td>' . htmlspecialchars($note['reponse_utilisateur']) . '</td>';
                            echo '<td>' . ($note['est_correct'] ? 'Oui' : 'Non') . '</td>';
                            echo '<td>' . htmlspecialchars($note['date']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo '<p>Aucune note pour le moment.</p>';
                    }
                }
            }
            ?>

            <!-- Afficher les resultats de l'utilisateur -->
            <h2>Derniers résultats</h2>
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>Type d'exercice</th>
                            <th>Question</th>
                            <th>Réponse attendue</th>
                            <th>Réponse utilisateur</th>
                            <th>Correct</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($notes as $note): ?>
                    <tr class="<?php echo $note['est_correct'] ? 'correct' : 'incorrect'; ?>">
                        <td><?php echo htmlspecialchars($note['type_exercice']); ?></td>
                        <td><?php echo htmlspecialchars($note['question']); ?></td>
                        <td><?php echo htmlspecialchars($note['reponse_attendue']); ?></td>
                        <td><?php echo htmlspecialchars($note['reponse_utilisateur']); ?></td>
                        <td><?php echo $note['est_correct'] ? 'Oui' : 'Non'; ?></td>
                        <td><?php echo htmlspecialchars($note['date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>

        </center>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
<?php
@ob_start();
include 'utils.php';
session_start();
require '../db.php'; // Connexion à la base de données

log_adresse_ip("logs/log.txt", "correction.php - ".$_SESSION['prenom']." - Question numéro ".$_SESSION['nbQuestion']);

if (empty($_POST['correction'])) {
    session_destroy();
    session_unset();
    unset($_POST);
    header('Location: ./index.php');
    exit();
}

$operation = $_POST['operation'];
$reponse_attendue = trim($_POST['correction']);
$reponse_utilisateur = trim($_POST['mot']);
$est_correct = ($reponse_utilisateur === $reponse_attendue) ? 1 : 0;
$type_exercice = 'addition';

// Enregistrement du résultat dans la base de données
$sql = "INSERT INTO exercices (user_id, type_exercice, question, reponse_attendue, reponse_utilisateur, est_correct) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id'], $type_exercice, $operation, $reponse_attendue, $reponse_utilisateur, $est_correct]);

$_SESSION['historique'] .= $est_correct ? "$operation $reponse_attendue\n" : "********$operation $reponse_utilisateur;$reponse_attendue\n";
if ($est_correct) {
    $_SESSION['nbBonneReponse']++;
}
?>

<?php include '../header.php'; ?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Correction</title>
</head>
<body style="background-color:grey;">
    <center>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:1000px;height:430px;background-image:url('../images/NO.jpg');background-repeat:no-repeat;">
                    <center>
                        <h1><?php echo $est_correct ? "Super " . $_SESSION['prenom'] . " ! Bonne réponse." : "Oh non !"; ?></h1>
                        <h2><?php if (!$est_correct) echo "La bonne réponse était : $operation $reponse_attendue."; ?></h2>
                        <br />
                        <p>Tu as <?php echo $_SESSION['nbBonneReponse']; ?> bonne(s) réponse(s) sur <?php echo $_SESSION['nbQuestion']; ?> question(s).</p>
                        <br /><br />
                        <form action="<?php echo $_SESSION['nbQuestion'] < $_SESSION['nbMaxQuestions'] ? './question.php' : './fin.php'; ?>" method="post">
                            <input type="submit" value="Suite" autofocus>
                        </form>
                    </center>
                </td>
                <td style="width:280px;height:430px;background-image:url('../images/NE.jpg');background-repeat:no-repeat;"></td>
            </tr>
            <tr>
                <td style="width:1000px;height:323px;background-image:url('../images/SO.jpg');background-repeat:no-repeat;"></td>
                <td style="width:280px;height:323px;background-image:url('../images/SE.jpg');background-repeat:no-repeat;"></td>
            </tr>
        </table>
    </center>
    <br />
</body>
</html>

<?php include '../footer.php'; ?>

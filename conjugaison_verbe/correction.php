<?php
@ob_start();
include 'utils.php';
session_start();
require '../db.php';

log_adresse_ip("logs/log.txt", "correction.php - " . $_SESSION['prenom'] . " - Question numéro " . $_SESSION['nbQuestion']);

if ($_POST['correction1'] == "" || $_POST['correction2'] == "" || $_POST['correction3'] == "" || $_POST['correction4'] == "" || $_POST['correction5'] == "" || $_POST['correction6'] == "") {
    session_destroy();
    session_unset();
    unset($_POST);
    header('Location: ./index.php');
    exit();
}

$type_exercice = 'conjugaison_verbe';
$nbPointsLocal = 0;
$pronoms = ['Je/J\'', 'Tu', 'Il/Elle/On', 'Nous', 'Vous', 'Ils/Elles'];

for ($i = 1; $i <= 6; $i++) {
    $question = $pronoms[$i - 1] . " ______";
    $reponse_attendue = trim($_POST['correction' . $i]);
    $reponse_utilisateur = trim($_POST['mot' . $i]);
    $est_correct = ($reponse_utilisateur === $reponse_attendue) ? 1 : 0;

    // Enregistrement de la réponse dans la base de données
    $sql = "INSERT INTO exercices (user_id, type_exercice, question, reponse_attendue, reponse_utilisateur, est_correct) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $type_exercice, $question, $reponse_attendue, $reponse_utilisateur, $est_correct]);

    if ($est_correct) {
        $_SESSION['nbBonneReponse']++;
        $nbPointsLocal++;
    }
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
                        <h2>Voici tes bonnes et mauvaises réponses !</h2>
                        <?php
                        for ($i = 1; $i <= 6; $i++) {
                            echo $pronoms[$i - 1] . " ";
                            if ($_POST['mot' . $i] === $_POST['correction' . $i]) {
                                echo $_POST['mot' . $i] . " ✅<br />";
                            } else {
                                echo "<strike>" . $_POST['mot' . $i] . "</strike> ❌ ➡ " . $_POST['correction' . $i] . "<br />";
                            }
                        }
                        ?>
                        <br />
                        <p>Tu as <?php echo $nbPointsLocal; ?> bonne(s) réponse(s) sur 6.</p>
                        <br /><br />
                        <form action="<?php echo $_SESSION['nbQuestion'] < $_SESSION['nbMaxQuestions'] ? './question.php' : './fin.php'; ?>" method="post">
                            <input type="submit" value="Suite" autofocus>
                        </form>
                        <br /><br />
                        <form action="./raz.php" method="post">
                            <input type="submit" value="Tout recommencer">
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

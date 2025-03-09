<?php
@ob_start();
include 'utils.php';

session_start();
$_SESSION['origine'] = "question";

// Vérifier si l'utilisateur est connecté pour accéder à la page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer le prénom de l'utilisateur
$_SESSION['prenom'] = $_SESSION['username'];

$numQuestion = $_SESSION['nbQuestion'] + 1;
log_adresse_ip("logs/log.txt", "question.php - " . $_SESSION['prenom'] . " - Question numéro " . $numQuestion);

include '../header.php';
require '../db.php';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Question</title>
</head>
<body style="background-color:grey;">
    <?php 
    $_SESSION['nbQuestion'] = $_SESSION['nbQuestion'] + 1;
    $fichier = file("listeQuestions.txt");
    $total = count($fichier);
    $alea = mt_rand(0, $total - 1);
    $ligneFichier = explode(';', $fichier[$alea]);
    $numPronom = mb_substr($ligneFichier[0], 0, 1);
    if ($numPronom == "*") {
        $numPronom = mt_rand(1, 6);
        switch ($numPronom) {
            case "1":
                $sujet = "Je";
                break;
            case "2":
                $sujet = "Tu";
                break;
            case "3":
                $alea = mt_rand(0, 2);
                $sujet = "Il";
                if ($alea == 0) $sujet = "Elle";
                if ($alea == 1) $sujet = "On";
                break;
            case "4":
                $sujet = "Nous";
                break;
            case "5":
                $sujet = "Vous";
                break;
            case "6":
                $sujet = "Ils";
                if (mt_rand(0, 1) == 0) $sujet = "Elles";
                break;
        }
    } else {
        $sujet = mb_substr($ligneFichier[0], 1);
    }
    $verbe = $ligneFichier[1];
    $finDePhrase = $ligneFichier[2];
    $bonneReponse = conjugaison("verbes/" . supprime_caracteres_speciaux($verbe) . "_present.txt", $numPronom);
    $bonneReponsescs = supprime_caracteres_speciaux($bonneReponse);
    if ($sujet == "Je" && (substr($bonneReponsescs, 0, 1) == "a" || substr($bonneReponsescs, 0, 1) == "e" || substr($bonneReponsescs, 0, 1) == "i" || substr($bonneReponsescs, 0, 1) == "o" || substr($bonneReponsescs, 0, 1) == "u")) {
        $sujet = "J'";
    }
    ?>
    <center>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:1000px;height:430px;background-image:url('../images/NO.jpg');background-repeat:no-repeat;">
                    <center>
                        <h1>Question Numéro <?php echo $_SESSION['nbQuestion']; ?></h1><br />
                        <h3>Conjugue le verbe <u><?php echo $verbe; ?></u> pour compléter cette phrase.</h3>
                        <form action="./correction.php" method="post">
                            <input type="hidden" name="sujet" value="<?php echo $sujet; ?>"></input>
                            <input type="hidden" name="correction" value="<?php echo $bonneReponse; ?>"></input>
                            <input type="hidden" name="finDePhrase" value="<?php echo $finDePhrase; ?>"></input>
                            <br />
                            <label for="fname"><?php echo $sujet; ?>&nbsp;</label>
                            <input type="text" id="mot" name="mot" autocomplete="off" autofocus>
                            <label for="fname"><?php echo $finDePhrase; ?>&nbsp;</label>
                            <br /><br /><br />
                            <input type="submit" value="Valider">
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
<?php
	@ob_start();
    include 'utils.php';

	session_start();
	$_SESSION['origine']="question";

    // Vérifier si l'utilisateur est connecté pour accéder à la page
	if (!isset($_SESSION['user_id'])) {
		header('Location: ../login.php');
		exit();
	}

	// Recuperer le prenom de l'utilisateur
	$_SESSION['prenom']=$_SESSION['username'];

    $numQuestion=$_SESSION['nbQuestion']+1;
    log_adresse_ip("logs/log.txt","question.php - ".$_SESSION['prenom']." - Question numéro ".$numQuestion);
?>

<?php
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
            $_SESSION['nbQuestion']=$_SESSION['nbQuestion']+1;
			$nbGauche=0;
			$nbDroite=0;
			$operation=0;
			$reponse=0;

			//$nbGauche=mt_rand(1,8)*10+mt_rand(5,9);
			//$nbDroite=mt_rand(9,99);
			$nbGauche=mt_rand(1000,10000);
			$nbDroite=mt_rand(5000,10000);
			$operation=$nbGauche.' + '.$nbDroite;
			$reponse=$nbGauche+$nbDroite;	
		?>
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('../images/NO.jpg');background-repeat:no-repeat;">
						<center>
							<h1>Question Numéro <?php echo "".$_SESSION['nbQuestion']."" ?></h1><br />
							<h3>Combien fait le calcul suivant ?</h3>
							<h3><?php echo ''.$operation.' = ?'; ?></h3>
							<form action="./correction.php" method="post">
								<input type="hidden" name="operation" value="<?php echo ''.$operation.' = ' ?>"></input>
								<input type="hidden" name="correction" value="<?php echo ''.$reponse.'' ?>"></input>
								<br />
								<label for="fname">Combien fait le calcul ci-dessus ? </label><br>
								<input type="text" id="mot" name="mot" autocomplete="off" autofocus><br /><br /><br />
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
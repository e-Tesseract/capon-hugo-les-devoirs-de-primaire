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

	// Récupérer le prénom de l'utilisateur
	$_SESSION['prenom'] = $_SESSION['username'];

    $numQuestion=$_SESSION['nbQuestion']+1;
    log_adresse_ip("logs/log.txt","question.php - ".$_SESSION['prenom']." - Question numéro ".$numQuestion);

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
			$fichier = file("listeDeMots/liste_dictee_20230407.txt");
			$total = count($fichier);
			$alea=mt_rand(0,$total-1);
			$ligneFichier=explode(';',$fichier[$alea]);
		?>
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('../images/NO.jpg');background-repeat:no-repeat;">
						<center>
							<h1>Question Numéro <?php echo "".$_SESSION['nbQuestion']."" ?></h1><br />
							<audio autoplay controls>
								<source src="./<?php echo './sons/'.$ligneFichier[1].''?>" type="audio/mpeg">
								Votre navigateur ne supporte pas l'audio. Passez à Firefox !
							</audio>
							<form action="./correction.php" method="post">
								<input type="hidden" name="correction" value="<?php echo ''.$ligneFichier[0].''?>"></input>
								<input type="hidden" name="nomFichierSon" value="<?php echo ''.$ligneFichier[1].''?>"></input>
								<br />
								<label for="fname">Qu'as-tu entendu ?</label><br>
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

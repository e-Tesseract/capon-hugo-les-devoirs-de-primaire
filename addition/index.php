<?php
	@ob_start();
	session_start();

	// verifier si l'utilisateur est connecté pour accéder à la page
	if (!isset($_SESSION['user_id'])) {
		header('Location: ../login.php');
		exit;
	}

	$nom = $_SESSION['username'];

    include 'utils.php';
    log_adresse_ip("logs/log.txt","index.php");
	$_SESSION['nbMaxQuestions']=10;
	$_SESSION['nbQuestion']=0;
	$_SESSION['nbBonneReponse']=0;
	$_SESSION['historique']="";
	$_SESSION['origine']="index";

	require '../header.php'; // Inclure l'en-tête
	require '../db.php'; // Inclure la connexion à la base de données
?>


<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Accueil</title>
	</head>
	<body style="background-color:grey;">
		<?php 
			$_POST['nbQuestion']=0;
			$_POST['nbBonneReponse']=0;
			$_POST['prenom']=$nom;
			$_POST['historique']="";
			$_POST['nbMaxQuestions']=10;
		?> 
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('../images/NO.jpg');background-repeat:no-repeat;">
						<center>
                        	<h1>Bonjour <?php echo $nom; ?> !</h1><br />
							<h2>Nous allons faire du calcul mental. Tu devras faire <?php echo ''.$_SESSION['nbMaxQuestions'].'' ?> calculs.</h2><br />
							<form action="./question.php" method="post">
								<input type="submit" value="Commencer">
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
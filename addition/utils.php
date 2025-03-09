<?php
// log adresse ip
// paramètre : nom du fichier de log
function log_adresse_ip($cheminFichierLog, $nomPage) {
    $adresseIP = $_SERVER['REMOTE_ADDR'];

    // Récupérer l'ID de l'utilisateur s'il est connecté
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "invité";

    // Obtenir la date et l'heure au format YYYY-MM-DD HH:MM:SS
    $dateHeure = date("Y-m-d H:i:s");

    // Vérifier si le dossier du fichier log existe, sinon le créer
    $dossierLog = dirname($cheminFichierLog);
    if (!file_exists($dossierLog)) {
        mkdir($dossierLog, 0777, true);
    }

    // Construire la ligne de log
    $log = "[$dateHeure] - IP: $adresseIP - User: $userID - Page: $nomPage\n";

    // Écrire dans le fichier log (ajout en fin de fichier)
    file_put_contents($cheminFichierLog, $log, FILE_APPEND);
}

?>

<?php
function supprime_caracteres_speciaux($chaine) { 
    $chaine=str_replace("à","a",$chaine);
    $chaine=str_replace("â","a",$chaine);
    $chaine=str_replace("é","e",$chaine);
    $chaine=str_replace("è","e",$chaine);
    $chaine=str_replace("ë","e",$chaine);
    $chaine=str_replace("ê","e",$chaine);
    $chaine=str_replace("î","i",$chaine);
    $chaine=str_replace("ï","i",$chaine);
    $chaine=str_replace("ô","o",$chaine);
    $chaine=str_replace("ö","o",$chaine);
    $chaine=str_replace("ù","u",$chaine);
    $chaine=str_replace("û","u",$chaine);
    $chaine=str_replace("ü","u",$chaine);
    $chaine=str_replace("ÿ","y",$chaine);
    $chaine=str_replace("ç","c",$chaine);
    return $chaine;
}
?>

<?php
function conjugaison($nomFichier, $numLigne) {
    $fichierVerbe = file($nomFichier);
    $reponse = $fichierVerbe[$numLigne-1];
    $reponse = substr($reponse,0,-1);
    return $reponse;
}
?>

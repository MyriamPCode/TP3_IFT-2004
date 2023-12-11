<!DOCTYPE html>
<html>

<head>
	<title>TP3 - Équipe 10</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"/>
</head>

<body>

<?php 
session_start();
include 'init.php';
include 'header.php';
include 'barre_etat.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $noUtilisateur = $_POST['noUser'];
    $mdpUtilisateur = $_POST['mdpUser'];
    
    $selectUser = "SELECT * FROM TP3_UTILISATEUR WHERE NO_UTILISATEUR = " . $noUtilisateur . " AND MOT_DE_PASSE_UTI = '" . $mdpUtilisateur . "'";
    $queryResult = oci_fetch_object(performDatabaseQuery($selectUser));

    if($queryResult -> NO_UTILISATEUR > -1){
        // Authentication successful
        session_start();
        $_SESSION["noUser"] = $noUtilisateur;
        $_SESSION["estConnecté"] = 1;
        $_SESSION['typeUser'] = $queryResult -> TYPE_UTI;
        $_SESSION['prenom'] = $queryResult -> PRENOM_UTI;
        $_SESSION['nom'] = $queryResult -> NOM_UTI;
        header("Location: liste_sondages.php");
        exit();
    } else {
        // Authentication failed      
        echo "<p>Numéro utilisateur ou mot de passe invalide.</p>";
    }
}
?>

<form method="post" action="">
	<p>
		<label for="noUser">No Utilisateur: </label>
    	<input type="text" id="noUser" name="noUser" />
	</p>
	<p>
		<label for="mdpUser">Mot de passe: </label>
    	<input type="password" id="mdpUser" name="mdpUser" />
	</p>
    <input type="submit" value="Submit" />
</form>

<?php include 'footer.php'; ?>
</body>

</html>


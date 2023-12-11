<?php
session_start();

include 'init.php';
include 'barre_etat.php';

date_default_timezone_set("America/Toronto");

$titreSondage = "";
$nomResponsable = "";
$date = "";

if(isset($_POST["rechercher"])){
    $titreSondage = $_POST["TITRE_SON"];
    $nomResponsable = $_POST["UTILISATEUR_PROJET"];
    if ($_POST["DATE_SON"] != ""){
    $date = $_POST["DATE_SON"];
    $rechercheDate = "and DATE_DEBUT_SON > to_date('".$date."', 'RRRR-MM-DD')
                            and DATE_FIN_SON < to_date('".$date."', 'RRRR-MM-DD')";
    } else {
        $$rechercheDate = "";
    }

    
    $rechercheSondage = "select * from TP3_SONDAGE S
                            where TITRE_SON like '%".$titreSondage."%'
                            and exists (select * from TP3_UTILISATEUR_PROJET P
                                        where NO_UTILISATEUR like '%".$nomResponsable."%'
                                        and S.CODE_PROJET = P.CODE_PROJET)" . $rechercheSondageArchive;
    
    $rechercheSondageArchive = "select * from TP3_SONDAGE_ARCHIVE S
                            where TITRE_SON like '%".$titreSondage."%'
                            and exists (select * from TP3_UTILISATEUR_PROJET P
                                        where NO_UTILISATEUR like '%".$nomResponsable."%'
                                        and S.CODE_PROJET = P.CODE_PROJET)" . $rechercheSondage;
    
    $_SESSION['querySondage'] = $rechercheSondage;
    $_SESSION['querySonArchive'] = $rechercheSondageArchive;
    $_SESSION["recherche"] = 1;
    header("location:liste_sondages.php");
    exit();
    }
?>

<!DOCTYPE html>

<html>
<head>
<title>Rechercher</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
<?php include 'header.php'; ?>

<form method="post" action="sondage_rechercher.php">
	<p>
    	<label for="titreSondage"> Titre du sondage:  </label><br>
    		<input type="text" name="TITRE_SON" value="<?php echo $titreSondage;?>"><br>
    	<label for="nomResponsable"> Nom du responsable: </label><br>
    		<input type="text" name="UTILISATEUR_PROJET" value="<?php echo $nomResponsable;?>"><br>
    	<label for="date"> Date du sondage:  </label><br>
    		<input type="text" name="DATE_SON" value="<?php echo $date;?>" placeholder="AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"><br><br>
    	
    	<input type="submit" name="rechercher" value="OK"><br>
		<input type="button" onclick="window.location.href='liste_sondages.php';" value="Annuler"/><br>
	</p>
</form>	

<?php include 'footer.php'; ?>
</body>

</html>
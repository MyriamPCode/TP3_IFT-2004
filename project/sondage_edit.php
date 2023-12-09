<?php
session_start();

include 'init.php';
?>

<html>
<head>
<title>Détails du sondage</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
<?php include 'header.php'; ?>
<?php include 'barre_etat.php'; ?>

<input type="text" name="NO_SONDAGE" value="<?php echo $noSondage;?>"><br>
<input type="date" name="NO_SONDAGE" value="<?php echo $dateCreation;?>"><br>
<input type="date" name="NO_SONDAGE" value="<?php echo $dateDebut;?>"><br>
<input type="date" name="NO_SONDAGE" value="<?php echo $dateFin;?>"><br>
<input type="text" name="NO_SONDAGE" value="<?php echo $titreSondage;?>"><br>

<select name="cars" id="cars">
 <option value="volvo">Volvo</option>
 <option value="saab">Saab</option>
 <option value="mercedes">Mercedes</option>
 <option value="audi">Audi</option>
</select>
<br>
<input type="text" name="NO_SONDAGE" value="<?php echo $projet;?>"><br>

</body>

</html>
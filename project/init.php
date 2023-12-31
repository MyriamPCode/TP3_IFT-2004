<?php
include 'credentials.php';

global $conn;
$conn = oci_connect($username, $password, 'fsg-p-ora01.fsg.ulaval.ca:1521/ora19c.fsg.ulaval.ca', 'AL32UTF8');
$where = '';
if(isset($_POST['Suivant']) ){
    $where = " where NO_UTILISATEUR > ". $_POST['NO_UTILISATEUR'];
}

$stid = oci_parse($conn, 'select * from TP3_UTILISATEUR ' . $where .
    ' fetch first 1 rows only');
oci_execute($stid);
$nomUtilisateur = "";
$noUtilisateur = "";
$mdpUtilisateur = "";

if(($utilisateur = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
    $nomUtilisateur = $utilisateur["NOM_UTI"]; $erreur = "";
    $noUtilisateur = $utilisateur["NO_UTILISATEUR"];
    $mdpUtilisateur = $utilisateur["MOT_DE_PASSE_UTI"];
}else{
    $erreur = "Aucun utilisateur ne correspond à la requête";
}

oci_close($conn);

function performDatabaseQuery($query) {
    global $conn;
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    return $stid;
}

function closeDatabaseConnection() {
    global $conn;
    oci_close($conn);
}

?>

<?php
include 'credentials.php';

global $conn;
$conn = oci_connect($username, $password, 'fsg-p-ora01.fsg.ulaval.ca:1521/ora19c.fsg.ulaval.ca', 'AL32UTF8');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$where = '';
if(isset($_POST['Suivant'])) {
    $where = " where NO_UTILISATEUR > ". $_POST['NO_UTILISATEUR'];
}

$query = 'SELECT * FROM TP3_UTILISATEUR ' . $where . ' fetch first 1 rows only'; // Define the $query variable

$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_execute($stid);
$nomUtilisateur = "";
$noUtilisateur = "";
$mdpUtilisateur = "";

if(($utilisateur = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
    $nomUtilisateur = $utilisateur["NOM_UTI"];
    $erreur = "";
    $noUtilisateur = $utilisateur["NO_UTILISATEUR"];
    $mdpUtilisateur = $utilisateur["MOT_DE_PASSE_UTI"];
} else {
    $erreur = "Aucun utilisateur ne correspond à la requête";
}

oci_close($conn);

function performDatabaseQuery($query, $conn) {
    $stid_query = oci_parse($conn, $query);
    
    if (!$stid_query) {
        $e = oci_error($conn);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    
    oci_execute($stid_query);
    return $stid_query;
}

$result = performDatabaseQuery($query, $conn);

function closeDatabaseConnection() {
    global $conn;
    oci_close($conn);
}
?>
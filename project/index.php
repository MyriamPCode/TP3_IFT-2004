<!DOCTYPE html>
<html>

<head>
	<title>TP3 - Équipe 10</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"/>
</head>

<body>
<?php include 'init.php'; ?>
<?php include 'header.php'; ?>
<?php 
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['utilisateur'])) {
    // Rediriger vers la page appropriée en fonction du type d'utilisateur
    switch ($_SESSION['utilisateur']['TYPE_UTI']) {
        case 'Administrateur':
            header('Location: liste_sondages.php'); // Remplacez par le nom de la page des sondages pour les administrateurs
            exit();
        case 'Responsable':
            header('Location: liste_sondages_responsable.php'); // Remplacez par le nom de la page des sondages pour les responsables
            exit();
        case 'Employé':
            header('Location: liste_sondages_employe.php'); // Remplacez par le nom de la page des sondages pour les employés
            exit();
    }
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noUtilisateur = $_POST['noUser'];
    $mdpUtilisateur = $_POST['mdpUser'];
    
    // Remplacez par votre logique de vérification du nom d'utilisateur et du mot de passe
    $utilisateur_valide = validerUtilisateur($noUtilisateur, $mdpUtilisateur);
    
    if ($utilisateur_valide) {
        // Enregistrez l'utilisateur dans la session
        $_SESSION['utilisateur'] = $utilisateur_valide;
        
        // Redirection vers la page appropriée en fonction du type d'utilisateur
        redirigerVersPageAppropriate($_SESSION['utilisateur']['TYPE_UTI']);
    } else {
        // Afficher un message d'erreur
        $message_erreur = "La combinaison numéro utilisateur + mot de passe est invalide.";
    }
}

function validerUtilisateur($noUtilisateur, $mdpUtilisateur) {
    // Utilisez PDO pour interagir avec la base de données
    $bdd = new PDO('mysql:host=Votre_Hote;dbname=Votre_Base_De_Donnees;charset=utf8', $username, $password);
    
    // Requête préparée pour récupérer les informations de l'utilisateur
    $requete = $bdd->prepare("SELECT * FROM TP3_UTILISATEUR WHERE NO_UTILISATEUR = :noUtilisateur AND MOT_DE_PASSE_UTI = :mdpUtilisateur");
    $requete->bindParam(':noUtilisateur', $noUtilisateur);
    $requete->bindParam(':mdpUtilisateur', $mdpUtilisateur);
    $requete->execute();
    
    // Récupérer le résultat
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
    
    // Fermer la connexion à la base de données
    $bdd = null;
    
    return $utilisateur;
}

function redirigerVersPageAppropriate($typeUtilisateur) {
    switch ($typeUtilisateur) {
        case 'Administrateur':
            header('Location: liste_sondages.php'); // Remplacez par le nom de la page des sondages pour les administrateurs
            exit();
        case 'Responsable':
            header('Location: liste_sondages_responsable.php'); // Remplacez par le nom de la page des sondages pour les responsables
            exit();
        case 'Employé':
            header('Location: liste_sondages_employe.php'); // Remplacez par le nom de la page des sondages pour les employés
            exit();
    }
}

?>

<?php 
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
        header("Location: principal.php");
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


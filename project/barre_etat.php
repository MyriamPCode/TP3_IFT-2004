<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"/>
    <title>Accueil</title>
</head>
<body>

<?php    
// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION["estConnecté"]) && $_SESSION["estConnecté"] == 1;
}

// Fonction pour afficher l'état de connexion
function afficherEtatConnexion() {
    if (estConnecte()) {
        // Utilisateur connecté
        $prenomNom = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
        $typeUtilisateur = $_SESSION['typeUser'];
        echo "Bienvenue $prenomNom ($typeUtilisateur) - <a href='index.php'>Déconnecter</a>";
    } else {
        // Utilisateur non connecté
        echo "<a href='index.php'>Connexion</a>";
    }
}

afficherEtatConnexion();
?>

<!-- Si l'utilisateur n'est pas connecté on redirige à la page connexion (index.html).
Il est inutile de rajouter un lien pour retourner à la page de connexion pour se connecter 
lorsqu'un utilisateur est déjà connecter. -->
</body>
</html>
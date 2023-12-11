<?php
include 'init.php';
    
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

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>

<!-- Votre contenu de page d'accueil ici -->

<div id="menu">
    <ul>
        <!-- Autres éléments de menu ici si nécessaire -->

        <!-- Lien de connexion -->
        <li><a href="index.php">Connexion</a></li>
    </ul>
</div>
</body>
</html>
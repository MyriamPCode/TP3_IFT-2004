<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>

<div id="menu">
    <ul>
        <!-- Lien de connexion -->
        <li><a href="index.php">Connexion</a></li>
    </ul>
</div>

<?php

// Démarrez la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION['utilisateur']);
}

// Fonction pour afficher l'état de connexion
function afficherEtatConnexion() {
    if (estConnecte()) {
        // Utilisateur connecté
        $prenomNom = $_SESSION['utilisateur']['prenom'] . ' ' . $_SESSION['utilisateur']['nom'];
        $typeUtilisateur = $_SESSION['utilisateur']['type'];
        echo "Bienvenue $prenomNom ($typeUtilisateur) - <a href='deconnexion.php'>Déconnecter</a>";
    } else {
        // Utilisateur non connecté
        echo "<a href='index.php'>Connexion</a>";
    }
}
?>

</body>
</html>

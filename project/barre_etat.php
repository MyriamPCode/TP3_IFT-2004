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

<?php

// Vérifiez le formulaire de connexion et définissez la session si l'authentification réussit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifiez les informations de connexion (exemple)
    $utilisateurValide = true; // Remplacez ceci par votre logique d'authentification

    if ($utilisateurValide) {
        // Démarrez la session si ce n'est pas déjà fait
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Définissez les informations de l'utilisateur dans la session (exemple)
        $_SESSION['utilisateur'] = [
            'prenom' => 'John',
            'nom' => 'Doe',
            'type' => 'Utilisateur', // Remplacez par le type d'utilisateur approprié
        ];

        // Redirigez vers la page d'accueil ou une autre page sécurisée
        header('Location: index.php');
        exit();
    }
}
?>

<?php
// bare-etat.php

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

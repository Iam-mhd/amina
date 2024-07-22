<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du client</title>
    <link rel="stylesheet" href="../../public/css/show.css">
</head>
<body>
    <?php
    require_once('../models/Client.php');
    require_once('../../config/database.php');

    // Vérifiez si l'ID du client est présent dans l'URL
    if (isset($_GET['id'])) {
        // Récupérez l'ID du client depuis l'URL
        $clientId = $_GET['id'];

        try {
            // Préparer la requête SQL pour récupérer un client par son ID
            $query = $db->prepare("SELECT * FROM clients WHERE id = ?");
            // Exécuter la requête avec l'ID du client à récupérer
            $query->execute([$clientId]);
            // Récupérer le résultat sous forme d'objet Client
            $client = $query->fetchObject('Client');

            // Vérifiez si le client existe
            if ($client) {
                // Affichez les détails du client ici
                echo '<div class="details-client">';
                echo '<h1>Détails du client</h1>';
                echo '<p>ID: ' . $client->id . '</p>';
                echo '<p>Nom: ' . $client->nom . '</p>';
                echo '<p>Adresse: ' . $client->adresse . '</p>';
                echo '<p>Téléphone: ' . $client->telephone . '</p>';
                echo '<p>Email: ' . $client->email . '</p>';
                echo '<p>Sexe: ' . $client->sexe . '</p>';
                echo '<p>Statut: ' . $client->statut . '</p>';
                echo'<img src="./public/images/details.png" alt="Image">';
                // Ajoutez ici d'autres informations du client que vous souhaitez afficher
                echo '</div>';
            } else {
                echo 'Client non trouvé';
            }
        } catch (PDOException $e) {
            // En cas d'erreur lors de la récupération du client, afficher le message d'erreur
            die("Erreur lors de la récupération du client : " . $e->getMessage());
        }
    } else {
        echo 'ID du client manquant';
    }
    ?>
</body>
</html>
<?php
require_once('../models/Client.php');

$controller = new ClientController();
$clients = $controller->getAllClients();


class ClientController
{
    // Méthode pour récupérer un client par son ID depuis la base de données
    public function get($id)
    {
        global $db;
        try {
            $query = $db->prepare("SELECT * FROM clients WHERE id = ?");
            $query->execute([$id]);
            $clientData = $query->fetch(PDO::FETCH_ASSOC);
    
            if (!$clientData) {
                echo "Aucun client trouvé avec l'ID $id.";
            }
    
            return $clientData;
        } catch (PDOException $e) {
            die("Erreur lors de la récupération du client : " . $e->getMessage());
        }
    }
    

    // Méthode pour afficher la liste des clients dans une version imprimable
    public function printList()
    {
        // Récupérer tous les clients
        $clients = $this->getAllClients();

        // Ajouter le lien vers le fichier CSS pour "printList"
        echo '<link rel="stylesheet" type="text/css" href="./public/css/style_printlist.css">';

        // Afficher l'en-tête pour l'impression
        echo '<h1>Liste des clients</h1>';
        echo '<h3>Cliquez sur la touche Ctrl+p de votre clavier pour imprimer la liste de tous les clients</h3>';

        // Afficher la liste des clients avec un div pour chaque client
        echo '<div class="client-list">';
        foreach ($clients as $client) {
            echo '<div class="client-info">';
            echo "<p>ID: {$client->id}</p>";
            echo "<p>Nom: {$client->nom}</p>";
            echo "<p>Adresse: {$client->adresse}</p>";
            echo "<p>Téléphone: {$client->telephone}</p>";
            echo "<p>Email: {$client->email}</p>";
            echo "<p>Sexe: {$client->sexe}</p>";
            echo "<p>Statut: {$client->statut}</p>";
            echo '</div>';
        }
        echo '</div>';
    }

    // Méthode pour mettre à jour un client existant dans la base de données
    public function updateClient($id, $nom, $adresse, $telephone, $email, $sexe, $statut)
    {
        global $db;

        try {
            // Préparer la requête SQL de mise à jour
            $query = $db->prepare("UPDATE clients SET nom=?, adresse=?, telephone=?, email=?, sexe=?, statut=? WHERE id=?");

            // Exécuter la requête avec les valeurs des paramètres
            $query->execute([$nom, $adresse, $telephone, $email, $sexe, $statut, $id]);
        } catch (PDOException $e) {
            // En cas d'erreur lors de la mise à jour, afficher le message d'erreur
            die("Erreur lors de la mise à jour du client : " . $e->getMessage());
        }
    }

    // Méthode pour créer un nouveau client dans la base de données
    public function createClient($nom, $adresse, $telephone, $email, $sexe, $statut)
    {
        global $db;

        try {
            // Préparer la requête SQL d'insertion
            $query = $db->prepare("INSERT INTO clients(nom, adresse, telephone, email, sexe, statut) VALUES (?, ?, ?, ?, ?, ?)");

            // Exécuter la requête avec les valeurs des paramètres
            $query->execute([$nom, $adresse, $telephone, $email, $sexe, $statut]);
        } catch (PDOException $e) {
            // En cas d'erreur lors de l'insertion, afficher le message d'erreur
            die("Erreur lors de la création du client : " . $e->getMessage());
        }
    }

    // Méthode pour récupérer tous les clients depuis la base de données
    public function getAllClients()
{
    global $db;

    try {
        $query = "SELECT * FROM clients";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_CLASS, 'Client');
        
        if (empty($clients)) {
            echo 'Aucun client trouvé.';
        }

        return $clients;
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des clients : " . $e->getMessage());
    }
}

    // Méthode pour supprimer un client par son ID dans la base de données
    public function deleteClient($id)
    {
        global $db;
        echo $id;
        try {
            // Préparer la requête SQL de suppression
            $query = $db->prepare("DELETE FROM clients WHERE id=?");

            // Exécuter la requête avec l'ID du client à supprimer
            $query->execute([$id]);
        } catch (PDOException $e) {
            // En cas d'erreur lors de la suppression, afficher le message d'erreur
            die("Erreur lors de la suppression du client : " . $e->getMessage());
        }
    }

    // Méthode pour filtrer les clients en fonction des paramètres (par exemple, par nom ou adresse)
    public function filterClients($params)
    {
        global $db;

        try {
            // Préparer la requête SQL de filtrage
            $query = $db->prepare("SELECT * FROM clients WHERE nom LIKE ? OR adresse LIKE ?");
            $params = "%{$params}%"; // Ajouter des jokers pour rechercher des correspondances partielles
            // Exécuter la requête avec les valeurs des paramètres
            $query->execute([$params, $params]);
            // Récupérer les résultats dans un tableau d'objets Client
            $filteredClients = $query->fetchAll(PDO::FETCH_CLASS, 'Client');

            return $filteredClients;
        } catch (PDOException $e) {
            // En cas d'erreur lors du filtrage, afficher le message d'erreur
            die("Erreur lors du filtrage des clients : " . $e->getMessage());
        }
    }

    // Méthode pour trier les clients en fonction du champ et de l'ordre dans la base de données
    public function sortClients($field, $order)
    {
        global $db;

        try {
            // Vérifier si le champ de tri est valide pour éviter les attaques par injection SQL
            $validFields = array('id', 'nom', 'adresse', 'telephone', 'email', 'sexe', 'statut');
            if (!in_array($field, $validFields)) {
                die("Champ de tri non valide");
            }

            // Préparer la requête SQL de tri
            $query = $db->prepare("SELECT * FROM clients ORDER BY $field $order");

            // Exécuter la requête
            $query->execute();

            // Récupérer les résultats dans un tableau d'objets Client
            $sortedClients = $query->fetchAll(PDO::FETCH_CLASS, 'Client');

            return $sortedClients;
        } catch (PDOException $e) {
            // En cas d'erreur lors du tri, afficher le message d'erreur
            die("Erreur lors du tri des clients : " . $e->getMessage());
        }
    }

    // Méthode pour exporter la liste des clients au format CSV
    public function exportCSV()
    {
        global $db;

        try {
            ob_clean();
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=clients.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, array('ID', 'Nom', 'Adresse', 'Téléphone', 'Email', 'Sexe', 'Statut'));

            // Exécuter la requête SQL pour récupérer tous les clients
            $query = $db->query("SELECT * FROM clients");

            // Récupérer les résultats dans un tableau associatif
            $clients = $query->fetchAll(PDO::FETCH_ASSOC);

            // Écrire les données dans le fichier CSV
            foreach ($clients as $client) {
                fputcsv($output, $client);
            }
            fclose($output);
            exit;
        } catch (PDOException $e) {
            // En cas d'erreur lors de l'exportation, afficher le message d'erreur
            die("Erreur lors de l'exportation des clients au format CSV : " . $e->getMessage());
        }
    }

    // Méthode pour exporter la liste des clients au format PDF
    public function exportPDF()
    {
        global $db;

        require_once('vendor/autoload.php');
        $mpdf = new \Mpdf\Mpdf();

        try {
            ob_clean();
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="clients.pdf"');

            // Requête SQL pour récupérer tous les clients
            $query = $db->query("SELECT * FROM clients");
            $clients = $query->fetchAll(PDO::FETCH_ASSOC);

            // Générer le contenu PDF
            $html = '<h1>Liste des clients</h1><table border="1"><tr><th>ID</th><th>Nom</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Sexe</th><th>Statut</th></tr>';
            foreach ($clients as $client) {
                $html .= '<tr>';
                $html .= '<td>' . $client['id'] . '</td>';
                $html .= '<td>' . $client['nom'] . '</td>';
                $html .= '<td>' . $client['adresse'] . '</td>';
                $html .= '<td>' . $client['telephone'] . '</td>';
                $html .= '<td>' . $client['email'] . '</td>';
                $html .= '<td>' . $client['sexe'] . '</td>';
                $html .= '<td>' . $client['statut'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';

            $mpdf->WriteHTML($html);
            $mpdf->Output();
            exit;
        } catch (Exception $e) {
            // En cas d'erreur lors de l'exportation, afficher le message d'erreur
            die("Erreur lors de l'exportation des clients au format PDF : " . $e->getMessage());
        }
    }

    // Méthode pour générer un rapport
    public function generateReport()
    {
        // Implémentez la logique pour générer des rapports selon vos besoins
    }
}
?>

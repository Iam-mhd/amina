<?php
require_once '../controllers/adminController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $controller = new AdminController();
    $controller->ajouterAdmin($nom, $email, $mot_de_passe);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Administrateur</title>
</head>
<body>
    <h1>Ajouter un Administrateur</h1>
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>
        <label for="mot_de_passe">Mot de Passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>
        <input type="submit" value="Ajouter">
    </form>
</body>
</html>

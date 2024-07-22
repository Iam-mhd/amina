<!DOCTYPE html>
<html>

<head>
    <title>Modifier le client</title>
    <link rel="stylesheet" href="../../public/css/edit.css">
    <script src="./public/js/edit.js"></script>
</head>

<body>
    

    
    <?php if ($client) : ?>
        
        <div class="formulaire">
        <div class="client-heading">
        <h1>Modifier le client</h1>
    </div>
            <form action="./index.php?action=edit&id=<?php echo $client->id; ?>" method="post">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo $client->nom; ?>" required><br>

                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" value="<?php echo $client->adresse; ?>" required><br>

                <label for="telephone">Téléphone :</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo $client->telephone; ?>" required><br>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?php echo $client->email; ?>" required><br>

                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe" required>
                    <option value="homme" <?php if ($client->sexe === 'homme') echo 'selected'; ?>>Homme</option>
                    <option value="femme" <?php if ($client->sexe === 'femme') echo 'selected'; ?>>Femme</option>
                    <option value="autre" <?php if ($client->sexe === 'autre') echo 'selected'; ?>>Autre</option>
                </select><br>

                <label for="statut">Statut :</label>
                <input type="radio" id="actif" name="statut" value="actif" <?php if ($client->statut === 'actif') echo 'checked'; ?> required>
                <label for="actif">Actif</label>
                <input type="radio" id="inactif" name="statut" value="inactif" <?php if ($client->statut === 'inactif') echo 'checked'; ?> required>
                <label for="inactif">Inactif</label><br>

                <input type="submit" value="Modifier">
            </form>
        </div>
        <div class="image-modif">
        <img src="./public/images/modif.png" alt="image">
    </div>

    <?php else : ?>
        <p>Client non trouvé</p>
    <?php endif; ?>

    
</body>

</html>
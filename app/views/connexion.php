<?php
require_once '../../config/database.php';

session_start();

$message = '';

// Gestion des connexions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification pour les super administrateurs
    $tableSuperAdmin = "SELECT * FROM superAdmin WHERE email = :email";
    $requete = $db->prepare($tableSuperAdmin);
    $requete->bindParam(':email', $email);
    $requete->execute();
    $superAdmin = $requete->fetch();

    if ($superAdmin) {
        if ($superAdmin['mot_de_passe'] === $password) {
            $_SESSION['id_superAdmin'] = $superAdmin['id'];
            $_SESSION['email'] = $superAdmin['email'];
            header('Location: homeSuperAdmin.php');
            exit;
        } else {
            $message = "Mot de passe incorrect pour le super administrateur.";
        }
    } else {
        // Vérification pour les administrateurs
        $tableAdmin = "SELECT * FROM admin WHERE email = :email";
        $requete = $db->prepare($tableAdmin);
        $requete->bindParam(':email', $email);
        $requete->execute();
        $admin = $requete->fetch();

        if ($admin) {
            if ($admin['mot_de_passe'] === $password) {
                $_SESSION['id_admin'] = $admin['id'];
                $_SESSION['email'] = $admin['email'];
                header('Location: homeAdmin.php');
                exit;
            } else {
                $message = "Mot de passe incorrect pour l'administrateur.";
            }
        } else {
            $message = "Adresse e-mail ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url('public/assets/img/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            margin-top: 0;
            color: #007bff;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 20px;
            color: #d9534f;
            text-align: center;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #fff;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h1>Connexion</h1>
        <form action="" method="POST">
            <input type="hidden" name="action" value="login">
            <label for="email">Adresse e-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>
    </div>
    <div class="footer">
    </div>
</body>
</html>

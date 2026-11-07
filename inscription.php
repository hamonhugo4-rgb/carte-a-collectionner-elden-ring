<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=elden_ring;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$erreurs_inscription = [];
$erreurs_connexion = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['action_inscription'])) {
        $pseudo = trim($_POST['pseudo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if (empty($pseudo) || empty($email) || empty($password)) {
            $erreurs_inscription[] = "Tous les champs sont obligatoires pour l'inscription.";
        }
        if ($password !== $password2) {
            $erreurs_inscription[] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($erreurs_inscription)) {
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo OR email = :email");
            $stmt->execute(['pseudo' => $pseudo, 'email' => $email]);
            
            if ($stmt->fetch()) {
                $erreurs_inscription[] = "Ce pseudo ou cet email est déjà utilisé.";
            } else {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                
                $insert = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, password) VALUES (:pseudo, :email, :password)");
                $insert->execute([
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'password' => $passwordHash
                ]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_pseudo'] = $pseudo;

                header('Location: profil.php');
                exit;
            }
        }
    }

    if (isset($_POST['action_connexion'])) {
        $identifiant = trim($_POST['identifiant'] ?? '');
        $password = $_POST['password_connexion'] ?? '';

        if (empty($identifiant) || empty($password)) {
            $erreurs_connexion[] = "Tous les champs sont obligatoires pour se connecter.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = :id OR email = :id");
            $stmt->execute(['id' => $identifiant]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_pseudo'] = $user['pseudo'];

                header('Location: profil.php');
                exit;
            } else {
                $erreurs_connexion[] = "Identifiant ou mot de passe incorrect.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte - Elden Ring</title>
    <link rel="stylesheet" href="css/projetfinalstyle.css" />
    <style>
        .auth-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            padding: 30px;
            font-family: monospace;
            color: #fff;
            margin-top: 50px;
        }
        .auth-box {
            background-color: rgb(69, 70, 62);
            border: 1rem solid #000;
            padding: 25px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .auth-box h2 {
            text-align: center;
            text-transform: uppercase;
            margin-top: 0;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
            color: #fff;
        }
        .auth-box form div {
            margin-bottom: 15px;
        }
        .auth-box form label {
            display: block;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            color: #fff;
        }
        .auth-box form input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            background: #222;
            border: 1px solid #555;
            color: #fff;
            font-family: monospace;
        }
        .error-zone {
            background-color: rgba(139, 0, 0, 0.8);
            color: #fff;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            list-style: none;
            padding-left: 5px;
        }
        .auth-box .btn-submit {
            width: 100%; 
            margin: 10px 0 0 0;
            background-color: #04AA6D;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-family: monospace;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 1rem;
        }
        .auth-box .btn-submit:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
   <header>
        <div class="head">
        <div class="item menu"><button class="btn_menu" onclick="openNav()">menu</button></div>
        <div class="item centre"><img src="image/elden ring.png" ></div>
        <div class="item droite">
            <button class="btn_menu">
                <a href="inscription.php">compte</a>
            </button>
            
        </div >
        
     </div >
     <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            
           <a href="pageprincipale.php">Accueil</a>
            <a href="Carte.php">Carte</a>
            <a href="information.php">Information</a>
            <a href="favori.php">Favori</a>
            <a href="profil.php">Profil</a>
            <a href="produitderive.php">Produit dérivée</a>
    </div>


    </header>

   <main>
        <div class="zonetexte" style="height: auto; margin-bottom: 50px; background: none; border: none;">
            <div class="auth-container">
                
                <div class="auth-box">
                    <h2>S'inscrire</h2>
                    
                    <?php if (!empty($erreurs_inscription)): ?>
                        <ul class="error-zone">
                            <?php foreach ($erreurs_inscription as $erreur): ?>
                                <li>⚠️ <?= htmlspecialchars($erreur) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <form action="" method="post">
                        <input type="hidden" name="action_inscription" value="1">
                        <div>
                            <label for="pseudo">pseudo</label>
                            <input type="text" id="pseudo" name="pseudo" required>
                        </div>
                        <div>
                            <label for="email">email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div>
                            <label for="password2">Confirmer le mot de passe</label>
                            <input type="password" id="password2" name="password2" required>
                        </div>
                        <button class="btn-submit" type="submit">Créer le compte</button>
                    </form>
                </div>

                <div class="auth-box">
                    <h2>Se connecter</h2>
                    
                    <?php if (!empty($erreurs_connexion)): ?>
                        <ul class="error-zone">
                            <?php foreach ($erreurs_connexion as $erreur): ?>
                                <li>⚠️ <?= htmlspecialchars($erreur) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <form action="" method="post">
                        <input type="hidden" name="action_connexion" value="1">
                        <div>
                            <label for="identifiant">Pseudo ou Email</label>
                            <input type="text" id="identifiant" name="identifiant" required>
                        </div>
                        <div>
                            <label for="password_connexion">Mot de passe</label>
                            <input type="password" id="password_connexion" name="password_connexion" required>
                        </div>
                        <button class="btn-submit" type="submit">Connexion</button>
                    </form>
                </div>

            </div>
        </div>
   </main>

   <script src='script.js'></script>
</body>
</html>
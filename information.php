<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=elden_ring', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$boss_info = ""; 


if (isset($_POST['trouver'])) {
    $stmt = $pdo->prepare('SELECT * FROM boss WHERE nom = :name');
    $stmt->execute(['name' => $_POST['nom']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $boss_info = "Boss trouvé ! <br>" .
                     "Nom : " . $user['nom'] . "<br>" .
                     "Région : " . $user['position'] . "<br>" .
                     "Difficulté : " . $user['difficulté'] . "<br>" . 
                     "Description : " . $user['description'];
    } else {
        $boss_info = "Ce boss n'existe pas dans la liste.";
    }
}


if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare('INSERT INTO boss (nom, position, difficulté, description) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_POST['nom'], $_POST['position'], $_POST['difficulte'], $_POST['description']]);
    $boss_info = "Le boss " . $_POST['nom'] . " a été ajouté !";
}


if (isset($_POST['modifier'])) {
    $stmt = $pdo->prepare('UPDATE boss SET position = ?, difficulté = ?, description = ? WHERE nom = ?');
    $stmt->execute([$_POST['position'], $_POST['difficulte'], $_POST['description'], $_POST['nom']]);
    $boss_info = "Le boss " . $_POST['nom'] . " a été mis à jour.";
}


if (isset($_POST['supprimer'])) {
    $stmt = $pdo->prepare('DELETE FROM boss WHERE nom = ?');
    $stmt->execute([$_POST['nom']]);
    $boss_info = "Le boss " . $_POST['nom'] . " a été supprimé.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Information Boss - Elden Ring</title>
    <link rel="stylesheet" href="css/projetfinalstyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    
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
        <img src="image/torrentXtarnished.png" id="character-torrent" alt="Animation Torrent">
        
        
        

        
        <div class="zonetexte auto">
            <h1>Trouver votre boss</h1>
            <form method="POST">
                <label>Nom du boss : </label>
                <input type="text" name="nom" required>
                <button type="submit" name="trouver">Chercher le boss</button>
                <?php if (isset($_POST['trouver']) && $boss_info != ""): ?>
                    <div class="php-message"><?php echo $boss_info; ?></div>
                <?php endif; ?>
            </form>
        </div>

        
        <div class="zonetexte auto">
            <h1>Modifier votre boss</h1>
            <form method="POST">
                <label>Nom :</label> <input type="text" name="nom" required>
                <label>Région :</label> <input type="text" name="position" required>
                <label>Difficulté :</label>
                <select name="difficulte">
                    <option value="facile">Facile</option>
                    <option value="moyen">Moyen</option>
                    <option value="dure">Dure</option>
                </select>
                <label>Description :</label> <textarea name="description"></textarea>
                <button type="submit" name="modifier">Modifier le boss</button>
                <?php if (isset($_POST['modifier']) && $boss_info != ""): ?>
                    <div class="php-message"><?php echo $boss_info; ?></div>
                <?php endif; ?>
            </form>
        </div>

        
        <div class="zonetexte auto">
            <h1>Ajouter un nouveau boss</h1>
            <form method="POST">
                <label>Nom :</label> <input type="text" name="nom" required>
                <label>Région :</label> <input type="text" name="position" required>
                <label>Difficulté :</label>
                <select name="difficulte">
                    <option value="facile">Facile</option>
                    <option value="moyen">Moyen</option>
                    <option value="dure">Dure</option>
                </select>
                <label>Description :</label> <textarea name="description"></textarea>
                <button type="submit" name="ajouter">Enregistrer le boss</button>
                <?php if (isset($_POST['ajouter']) && $boss_info != ""): ?>
                    <div class="php-message"><?php echo $boss_info; ?></div>
                <?php endif; ?>
            </form>
        </div>

        
        <div class="zonetexte auto">
            <h1>Supprimer un boss</h1>
            <form method="POST">
                <label>Nom du boss : </label>
                <input type="text" name="nom" required>
                <button type="submit" name="supprimer">Supprimer le boss</button>
                <?php if (isset($_POST['supprimer']) && $boss_info != ""): ?>
                    <div class="php-message" style="border-color: red;"><?php echo $boss_info; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script> 
    <script>
        const lightbox = GLightbox();
    </script>
    <script src='script.js'></script>
</body>
</html>
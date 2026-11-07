<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: inscription.php');
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=elden_ring;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$id_user = $_SESSION['user_id'];


if (isset($_POST['retirer_id_carte'])) {
    $id_carte_a_retirer = intval($_POST['retirer_id_carte']);
    $stmtUpdate = $pdo->prepare("UPDATE utilisateurs_cartes SET est_favori = 0 WHERE id_utilisateur = :id_user AND id_carte = :id_carte");
    $stmtUpdate->execute([
        'id_user' => $id_user,
        'id_carte' => $id_carte_a_retirer
    ]);
    
    header('Location: favori.php');
    exit;
}


$stmtFav = $pdo->prepare("
    SELECT c.id, c.id_api, c.rareté 
    FROM utilisateurs_cartes uc 
    JOIN cartes c ON uc.id_carte = c.id 
    WHERE uc.id_utilisateur = :id_user AND uc.est_favori = 1
");
$stmtFav->execute(['id_user' => $id_user]);
$liste_favoris_bdd = $stmtFav->fetchAll(PDO::FETCH_ASSOC);


$mes_favoris = [];
foreach ($liste_favoris_bdd as $fav) {
    $json = @file_get_contents("https://eldenring.fanapis.com/api/bosses/" . $fav['id_api']);
    if ($json) {
        $array = json_decode($json, true);
        $mes_favoris[] = [
            'id' => $fav['id'],
            'nom' => $array["data"]["name"] ?? "Inconnu",
            'rarete' => $fav['rareté'],
            'image' => $array["data"]["image"] ?? "",
            'description' => $array["data"]["description"] ?? ""
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favori</title>
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
        <div class="zonegallerie">
            <h1>mes favoris</h1>
            <div id="mon-portfolio-galerie">
                
                <?php if (empty($mes_favoris)): ?>
                    <p class='vide-msg' style='color: gray;'>Vous n'avez pas encore de favoris.</p>
                <?php else: ?>
                    <?php foreach ($mes_favoris as $carte): ?>
                        <div class="gallery-item-wrapper" style="position: relative; display: inline-block;">
                            <a href="<?= $carte['image'] ?>" class="glightbox" data-gallery="mon-portfolio" data-id="<?= $carte['nom'] ?>" data-title="<?= $carte['nom'] ?> (<?= $carte['rarete'] ?>)">
                                <div class="elden-card <?= $carte['rarete'] ?>">
                                    <div class="card-header">
                                        <div class="card-name <?= $carte['rarete'] ?>"><?= $carte['nom'] ?></div>
                                        
                                        
                                        <form method="POST" action="favori.php" style="display: inline;" onclick="event.stopPropagation();">
                                            <input type="hidden" name="retirer_id_carte" value="<?= $carte['id'] ?>">
                                            <button type="submit" class="remove-fav-btn">❌</button>
                                        </form>

                                        <div class="card-rarity <?= $carte['rarete'] ?>"><?= $carte['rarete'] ?></div>
                                    </div>

                                    <div class="card-image-container">
                                        <img src="<?= $carte['image'] ?>" alt="Illustration Carte" class="card-image">
                                    </div>

                                    <div class="card-description-container">
                                        <p class="card-text"><?= $carte['description'] ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer></footer>

    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src='script.js'></script>
    <script>
        let lightbox = GLightbox({ selector: '.glightbox' });
    </script>
</body>
</html>
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
    die("Erreur : " . $e->getMessage());
}

$id_user = $_SESSION['user_id'];
$booster = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_favori'])) {
    $id_carte = intval($_POST['id_carte']);

    $stmtCheck = $pdo->prepare("SELECT est_favori FROM utilisateurs_cartes WHERE id_utilisateur = :id_user AND id_carte = :id_carte");
    $stmtCheck->execute(['id_user' => $id_user, 'id_carte' => $id_carte]);
    $actuel = $stmtCheck->fetchColumn();

    if ($actuel !== false) {
        $nouveau = ($actuel == 1) ? 0 : 1;

        $stmtUpdate = $pdo->prepare("UPDATE utilisateurs_cartes SET est_favori = :nouveau WHERE id_utilisateur = :id_user AND id_carte = :id_carte");
        $stmtUpdate->execute([
            'nouveau' => $nouveau,
            'id_user' => $id_user,
            'id_carte' => $id_carte
        ]);
    }

    header('Location: Carte.php');
    exit;
}


if (isset($_GET['ouvrir'])) {
    $nb_carte = 6;

    for ($i = 1; $i <= $nb_carte; $i++) {
        $seed = rand(0, 150);

        $stmt = $pdo->prepare('
            SELECT c.id, c.id_api, c.rareté, cache.nom, cache.image, cache.description 
            FROM cartes c
            LEFT JOIN api_bosses_cache cache ON c.id_api = cache.id_api
            WHERE c.rareté = :rarete 
            ORDER BY RAND(' . (string) $seed . ')
        ');
        $alea = rand(0, 100);

        $rarete = "commun";
        if ($alea <= 2) {
            $rarete = "legendaire";
        } elseif ($alea <= 10) {
            $rarete = "epique";
        } elseif ($alea <= 40) {
            $rarete = "rare";
        }

        $stmt->execute(['rarete' => $rarete]);
        $carte_bdd = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($carte_bdd) {
            $nom = $carte_bdd['nom'];
            $image = $carte_bdd['image'];
            $description = $carte_bdd['description'];


            if (empty($nom)) {
                $json = @file_get_contents("https://eldenring.fanapis.com/api/bosses/" . $carte_bdd['id_api']);
                if ($json) {
                    $array = json_decode($json, true);
                    $nom = $array["data"]["name"];
                    $image = $array["data"]["image"];
                    $description = $array["data"]["description"];


                    $stmtCache = $pdo->prepare("INSERT IGNORE INTO api_bosses_cache (id_api, nom, image, description) VALUES (:id_api, :nom, :img, :descr)");
                    $stmtCache->execute([
                        'id_api' => $carte_bdd['id_api'],
                        'nom' => $nom,
                        'img' => $image,
                        'descr' => $description
                    ]);
                }
            }

            $booster[] = [$nom, $carte_bdd['rareté'], $image, $description];

            $stmtInsert = $pdo->prepare("INSERT IGNORE INTO utilisateurs_cartes (id_utilisateur, id_carte, est_favori) VALUES (:id_user, :id_carte, 0)");
            $stmtInsert->execute([
                'id_user' => $id_user,
                'id_carte' => $carte_bdd['id']
            ]);
        }
    }
}


$stmtCollection = $pdo->prepare("
    SELECT c.id, c.rareté, uc.est_favori, cache.nom, cache.image, cache.description 
    FROM utilisateurs_cartes uc 
    JOIN cartes c ON uc.id_carte = c.id 
    LEFT JOIN api_bosses_cache cache ON c.id_api = cache.id_api
    WHERE uc.id_utilisateur = :id_user
");
$stmtCollection->execute(['id_user' => $id_user]);
$liste_cartes_obtenues = $stmtCollection->fetchAll(PDO::FETCH_ASSOC);

$ma_galerie = [];
foreach ($liste_cartes_obtenues as $co) {
    $id_carte = $co['id'];

    if (!isset($ma_galerie[$id_carte])) {
        $ma_galerie[$id_carte] = [
            'id' => $id_carte,
            'nom' => $co['nom'] ?? "Inconnu",
            'rarete' => $co['rareté'],
            'image' => $co['image'] ?? "",
            'description' => $co['description'] ?? "",
            'est_favori' => $co['est_favori']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte</title>
    <link rel="stylesheet" href="css/projetfinalstyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
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
        <div style="text-align: center; margin-top: 20px;">
            <a href="Carte.php?ouvrir=1" id="toggle-booster-btn" class="button"
                style="text-decoration: none; display: inline-block; padding: 10px 20px; background-color: #04AA6D; color: white; border-radius: 4px;">ouvrir
                un pack</a>
        </div>

        <?php if (!empty($booster)): ?>
            <div class="swiper mySwiper espace" id="booster" style="display: block !important;">
                <div class="swiper-wrapper">
                    <?php foreach ($booster as $carte): ?>
                        <div class="swiper-slide">
                            <div class="elden-card <?= $carte[1] ?>">
                                <div class="card-header">
                                    <div class="card-name <?= $carte[1] ?>"><?= $carte[0]; ?></div>
                                    <div class="card-rarity <?= $carte[1] ?>"><?= $carte[1]; ?></div>
                                </div>
                                <div class="card-image-container">
                                    <img src="<?= $carte[2]; ?>" alt="Illustration Carte" class="card-image">
                                </div>
                                <div class="card-description-container">
                                    <p class="card-text"><?= $carte[3]; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php endif; ?>

        <div class="zonegallerie">
            <h1>MES CARTES</h1>


            <div class="filter-container">
                <button class="filter-btn active" data-filter="all">Tout voir</button>
                <button class="filter-btn" data-filter="commun">Commun</button>
                <button class="filter-btn" data-filter="rare">Rare</button>
                <button class="filter-btn" data-filter="epique">Épique</button>
                <button class="filter-btn" data-filter="legendaire">Légendaire</button>
            </div>

            <div id="mon-portfolio-galerie">
                <?php if (empty($ma_galerie)): ?>
                    <p class='vide-msg' style='color: gray;'>Votre galerie est vide pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($ma_galerie as $carte): ?>
                        <div class="gallery-item-wrapper" data-rarete="<?= $carte['rarete'] ?>"
                            style="position: relative; display: inline-block;">
                            <a href="cartesdetaille.php?id=<?= $carte['id'] ?>" class="card-detail-link"
                                style="text-decoration: none; color: inherit; display: block;">
                                <div class="elden-card <?= $carte['rarete'] ?>">
                                    <div class="card-header">
                                        <div class="card-name <?= $carte['rarete'] ?>"><?= $carte['nom'] ?></div>


                                        <form method="POST" action="Carte.php" style="display: inline;"
                                            onclick="event.stopPropagation();">
                                            <input type="hidden" name="id_carte" value="<?= $carte['id'] ?>">
                                            <input type="hidden" name="action_favori" value="1">
                                            <button type="submit" class="fav-btn">
                                                <?= $carte['est_favori'] == 1 ? '⭐' : '☆' ?>
                                            </button>
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src='script.js'></script>

</body>

</html>
<?php 
try {
    $pdo = new PDO('mysql:host=localhost;dbname=elden_ring;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: Carte.php');
    exit;
}

$id_carte = intval($_GET['id']);

$stmt = $pdo->prepare("
    SELECT c.id, c.rareté, cache.nom, cache.image, cache.description, c.id_api 
    FROM cartes c
    LEFT JOIN api_bosses_cache cache ON c.id_api = cache.id_api
    WHERE c.id = :id_carte
");
$stmt->execute(['id_carte' => $id_carte]);
$boss = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$boss) {
    die("Boss introuvable.");
}

$loots = [];
$location_name = "Inconnue";
$location_image = "";

if (!empty($boss['id_api'])) {
    $json_boss = @file_get_contents("https://eldenring.fanapis.com/api/bosses/" . $boss['id_api']);
    if ($json_boss) {
        $api_data = json_decode($json_boss, true);
        if (isset($api_data['data'])) {
            $loots = $api_data['data']['drops'] ?? [];
            $location_name = $api_data['data']['location'] ?? "Inconnue";
            
           
            if (count($loots) === 1 && strtoupper(trim($loots[0])) === 'N/A') {
                $loots = [];
            }
            
            if ($location_name !== "Inconnue") {
                $json_loc = @file_get_contents("https://eldenring.fanapis.com/api/locations?name=" . urlencode(trim($location_name)));
                if ($json_loc) {
                    $loc_data = json_decode($json_loc, true);
                    if (!empty($loc_data['data']) && isset($loc_data['data'][0]['image'])) {
                        $location_image = $loc_data['data'][0]['image'];
                    }
                }
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
    <title>Détails - <?= htmlspecialchars($boss['nom'] ?? 'Boss') ?></title>
    <link rel="stylesheet" href="css/projetfinalstyle.css" />
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

    <main style="display: flex; flex-direction: column; align-items: center; margin-top: 50px; padding-bottom: 50px;">
        
        <div class="elden-card <?= $boss['rareté'] ?>" style="width: 450px; height: auto; padding: 20px; margin-bottom: 30px;">
            <div class="card-header">
                <h1 class="card-name <?= $boss['rareté'] ?>" style="font-size: 1.5rem; margin: 0;"><?= htmlspecialchars($boss['nom'] ?? 'Inconnu') ?></h1>
                <span class="card-rarity <?= $boss['rareté'] ?>"><?= htmlspecialchars($boss['rareté']) ?></span>
            </div>
            
            <div class="card-image-container" style="height: 300px; margin: 20px 0;">
                <img src="<?= htmlspecialchars($boss['image'] ?? '') ?>" alt="<?= htmlspecialchars($boss['nom'] ?? '') ?>" class="card-image">
            </div>

            <div class="card-description-container" style="height: auto; min-height: 120px; margin-bottom: 15px;">
                <h3 style="color: #fff; margin-top: 0;">Lore du Boss :</h3>
                <p class="card-text" style="font-size: 14px; line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($boss['description'] ?? 'Aucune description disponible.')) ?>
                </p>
            </div>

            <div style="background-color: #1a1a1a; padding: 12px; border-radius: 12px; border: 1px solid #333; margin-top: 10px;">
                <h3 style="color: #c5a059; margin-top: 0; font-family: 'Georgia', serif; font-size: 1rem;">Récompenses de victoire (Loot) :</h3>
                <?php if (!empty($loots)): ?>
                    <ul style="color: #e0e0e0; font-family: Arial, sans-serif; font-size: 13px; padding-left: 20px; margin: 0;">
                        <?php foreach ($loots as $loot): ?>
                            <li><?= htmlspecialchars($loot) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: #aaa; font-size: 13px; margin: 0; font-style: italic; padding-left: 5px;">
                        L'API n'a pas trouvé de loot pour ce boss.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="zonetexte auto" style="width: 450px; margin-top: 0; margin-left: 0; padding: 20px; box-sizing: border-box; border-radius: 20px; color: white;">
            <h2 style="font-family: 'Georgia', serif; text-align: center; border-bottom: 2px solid #c5a059; padding-bottom: 10px; margin-top: 0;">
                Emplacement dans l'Entre-terre
            </h2>
            <p style="font-family: monospace; text-align: center; font-size: 1.1rem; color: #fff; background: rgba(0,0,0,0.4); padding: 8px; border-radius: 6px;">
                📍 <?= htmlspecialchars($location_name) ?>
            </p>

            <?php if (!empty($location_image)): ?>
                <div style="width: 100%; height: 220px; border-radius: 12px; overflow: hidden; border: 2px solid #c5a059; margin-top: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.6);">
                    <img src="<?= htmlspecialchars($location_image) ?>" alt="Visuel de la zone" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            <?php else: ?>
                <div style="width: 100%; padding: 40px 0; border: 1px dashed #555; text-align: center; color: #aaa; font-size: 14px; font-style: italic; border-radius: 12px; margin-top: 15px; background-color: rgba(0,0,0,0.2);">
                    L'API n'a pas trouvé d'image pour ce lieu.
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="Carte.php" class="button" style="text-decoration: none; display: inline-block; padding: 10px 20px; background-color: #4f4e4e; color: white; border-radius: 4px;">Retour aux cartes</a>
        </div>

    </main>

    <script src='script.js'></script>
</body>
</html>
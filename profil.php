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


$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $id_user]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: inscription.php');
    exit;
}


$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs_cartes WHERE id_utilisateur = :id");
$stmtTotal->execute(['id' => $id_user]);
$totalCartes = $stmtTotal->fetchColumn();


$stmtFav = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs_cartes WHERE id_utilisateur = :id AND est_favori = 1");
$stmtFav->execute(['id' => $id_user]);
$totalFavoris = $stmtFav->fetchColumn();


$stmtCommun = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs_cartes uc JOIN cartes c ON uc.id_carte = c.id WHERE uc.id_utilisateur = :id AND c.rareté = 'commun'");
$stmtCommun->execute(['id' => $id_user]);
$totalCommun = $stmtCommun->fetchColumn();


$stmtRare = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs_cartes uc JOIN cartes c ON uc.id_carte = c.id WHERE uc.id_utilisateur = :id AND c.rareté = 'rare'");
$stmtRare->execute(['id' => $id_user]);
$totalRare = $stmtRare->fetchColumn();


$stmtEpique = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs_cartes uc JOIN cartes c ON uc.id_carte = c.id WHERE uc.id_utilisateur = :id AND c.rareté = 'epique'");
$stmtEpique->execute(['id' => $id_user]);
$totalEpique = $stmtEpique->fetchColumn();


$stmtLegendaire = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs_cartes uc JOIN cartes c ON uc.id_carte = c.id WHERE uc.id_utilisateur = :id AND c.rareté = 'legendaire'");
$stmtLegendaire->execute(['id' => $id_user]);
$totalLegendaire = $stmtLegendaire->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Elden Ring</title>
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

   <main>
        <div class="profile-main-container">
            <div class="profile-title">Mon Profil</div>

            <div class="user-status-block">
                <div class="user-avatar-circle">
                    <img src="image/profil.png" height="85%">
                </div>
                <div class="user-info-text">
                    <h3><?= htmlspecialchars($user['pseudo']) ?></h3>
                    <p>Statut : Porteur de Doigts</p>
                    <p ><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number"><?= $totalCartes ?></span>
                    <span class="stat-label">Cartes collectionnées</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= $totalFavoris ?></span>
                    <span class="stat-label">Favoris</span>
                </div>
            </div>

            <div class="rarity-section">
                <div class="rarity-title">Répartition par rareté</div>
                <div class="rarity-row commun">
                    <span>Commun</span>
                    <span><?= $totalCommun ?></span>
                </div>
                <div class="rarity-row rare">
                    <span>Rare</span>
                    <span><?= $totalRare ?></span>
                </div>
                <div class="rarity-row epique">
                    <span>Épique</span>
                    <span><?= $totalEpique ?></span>
                </div>
                <div class="rarity-row legendaire">
                    <span>Légendaire</span>
                    <span><?= $totalLegendaire ?></span>
                </div>
            </div>

            
            <div class="logout-container">
                <a href="deconnexion.php" class="logout-btn">Déconnexion</a>
            </div>
        </div>
   </main>

   <script src='script.js'></script>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>carte à collectionner lotr</title>
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
    <img src="image/torrentXtarnished.png" id="character-torrent" alt="Animation Torrent">
    <main>
        <button class="tab first" id="tab1" onclick="intro()">Introduction</button><button class="tab" id="tab2">Histoire</button>
        <button class="tab" id="tab3">mecanique</button><button class="tab" id="tab4">developpeur</button>
        <div class="zonetab">
            <img src="image/minitarnished.png" id="character-anim" alt="roulade">
            <div class="texte-gauche">
                <a id="p"> 
                    Elden Ring est une œuvre magistrale qui a redéfini les standards de l'action-RPG en monde ouvert dès sa sortie en 2022. Développé par les équipes légendaires de FromSoftware, le titre propose une immersion totale dans l'Entre-terre, un univers vaste et mélancolique où chaque colline cache un secret ou un danger mortel. Le jeu se distingue par sa direction artistique époustouflante et sa liberté d'approche sans précédent, permettant aux joueurs de choisir leur propre chemin, que ce soit par la force brute, la magie ancienne ou la discrétion. Récompensé par des centaines de titres de "Jeu de l'année", il a su séduire tant les néophytes que les vétérans des "Souls" par son équilibre parfait entre exploration contemplative et affrontements dantesques contre des boss à la mise en scène cinématographique.
                </a>
            </div>
            <div class="image-droite">
                <img class="image" id="i" src="image/intro.jpg">
            </div>
            
            
        </div>
        <div class="zonetexte">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/pjW1lTv494Y" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="divbadge" >
            <span class="badge">EH MAINTENANT COLLECTIONNE TOUTE LES CARTES ELDEN RING</span>
        </div>
    </main>
    <footer>
        
    </footer>
    <script src='script.js'></script>
</body>
</html>
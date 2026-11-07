let tab1 = document.getElementById("tab1");
let tab2 = document.getElementById("tab2");
let tab3 = document.getElementById("tab3");
let tab4 = document.getElementById("tab4");
let texte = document.querySelector("#p");
let image = document.querySelector("#i");
let character = document.getElementById("character-anim");
let torrent = document.getElementById("character-torrent");

function triggerAnimation() {
    let character = document.getElementById("character-anim");
    if (character) {
        character.classList.remove("run-anim");
        void character.offsetWidth;
        character.classList.add("run-anim");
    }
}

function intro() {
    if (texte && image) {
        texte.innerHTML = `Elden Ring est une œuvre magistrale qui a redéfini les standards de l'action-RPG en monde ouvert dès sa sortie en 2022. Développé par les équipes légendaires de FromSoftware, le titre propose une immersion totale dans l'Entre-terre, un univers vaste et mélancolique où chaque colline cache un secret ou un danger mortel. Le jeu se distingue par sa direction artistique époustouflante et sa liberté d'approche sans précédent, permettant aux joueurs de choisir leur propre chemin, que ce soit par la force brute, la magie ancienne ou la discrétion. Récompensé par des centaines de titres de "Jeu de l'année", il a su séduire tant les néophytes que les vétérans des "Souls" par son équilibre parfait entre exploration contemplative et affrontements dantesques contre des boss à la mise en scène cinématographique.`;
        image.src = "image/intro.jpg";
    }
}

function histoire() {
    if (texte && image) {
        texte.innerHTML = `L'histoire d'Elden Ring, co-écrite par le créateur du jeu Hidetaka Miyazaki et le célèbre romancier George R.R. Martin (auteur du Trône de Fer), plonge le joueur dans les ruines d'un royaume jadis glorieux : l'Entre-terre. Cet univers a été brisé lors d'un cataclysme connu sous le nom d'Éclatement, déclenché par la destruction de l'Elden Ring, la source vitale de l'Arbre-Monde. Les demi-dieux, enfants de la Reine Marika l'Éternelle, se sont alors approprié les éclats de l'anneau, appelés Runes Majeures, déclenchant une guerre impitoyable et sombrant dans la folie de leur puissance retrouvée. Le joueur incarne un Sans-éclat, un exilé autrefois privé de la Grâce divine, rappelé d'entre les morts pour traverser ces terres désolées, vaincre les demi-dieux déchus, restaurer le Cercle d'Elden et réclamer le trône de Seigneur d'Elden.`;
        image.src = "image/GeorgeRRMARTIN.webp";
    }
}

function mecanique() {
    if (texte && image) {
        texte.innerHTML = `Le gameplay d'Elden Ring sublime la formule exigeante des "Soulsborne" en l'adaptant à une liberté d'exploration totale en monde ouvert. Les combats demandent de la précision, de la patience et une bonne gestion de l'endurance, que l'on opte pour des épées massives, de la magie gravitationnelle ou des incantations sacrées. Le jeu introduit des mécaniques majeures : l'utilisation de Torrent, une monture spectrale indispensable pour explorer les immensités et mener des combats équestres dynamiques, ainsi que le système des Cendres d'Esprit, permettant d'invoquer les fantômes de guerriers ou de créatures terrassées pour obtenir du soutien face aux boss les plus redoutables. L'exploration est également récompensée par la découverte de donjons optionnels, de galeries souterraines géantes et d'armes uniques, offrant une rejouabilité infinie.`;
        image.src = "image/mecanique.jpg";
    }
}

function developpeur() {
    if (texte && image) {
        texte.innerHTML = `FromSoftware est le studio de développement japonais légendaire derrière Elden Ring, mondialement reconnu pour avoir fondé le genre exigeant du "Soulslike". Dirigé par le visionnaire Hidetaka Miyazaki, le studio s'est forgé une réputation d'excellence grâce à des titres cultes tels que Demon's Souls, la trilogie Dark Souls, Bloodborne et Sekiro: Shadows Die Twice. Caractérisés par leur narration environnementale cryptique, leur level design interconnecté et leur difficulté punitive mais gratifiante, les jeux de FromSoftware partagent tous une atmosphère dark fantasy unique. Avec Elden Ring, leur projet le plus ambitieux, ils ont réussi l'exploit de transposer leur philosophie de conception intransigeante dans un monde ouvert titanesque, vendant plus de 25 millions d'exemplaires et cimentant leur place parmi les plus grands créateurs de l'histoire du jeu vidéo.`;
        image.src = "image/miyazaki.png";
    }
}

if (tab1) {
    tab1.addEventListener('click', function(){
        triggerAnimation();
        intro();
    });
}
if (tab2) {
    tab2.addEventListener('click', function(){
        triggerAnimation();
        histoire();
    });
}
if (tab3) {
    tab3.addEventListener('click', function(){
        triggerAnimation();
        mecanique();
    });
}
if (tab4) {
    tab4.addEventListener('click', function(){
        triggerAnimation();
        developpeur();
    });
}

function openNav() {
    let sidenav = document.getElementById("mySidenav");
    if (sidenav) {
        sidenav.style.width = "250px";
    }
    
    if (torrent) {
        torrent.classList.remove("invoke-anim"); 
        void torrent.offsetWidth;                
        torrent.classList.add("invoke-anim");    
    }
}

function closeNav() {
    let sidenav = document.getElementById("mySidenav");
    if (sidenav) {
        sidenav.style.width = "0";
    }
    
    if (torrent) {
        torrent.classList.remove("invoke-anim");
    }
}

if (document.querySelector('.glightbox')) {
    let lightbox = GLightbox({ selector: '.glightbox' });
}

if (document.getElementById('booster')) {
    var swiper = new Swiper(".mySwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: { rotate: 35, stretch: 0, depth: 100, modifier: 0.55, slideShadows: true },
        pagination: { el: ".swiper-pagination" },
    });
}

const filterButtons = document.querySelectorAll('.filter-btn');
const cards = document.querySelectorAll('.gallery-item-wrapper');

if (filterButtons.length > 0) {
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const filterValue = button.getAttribute('data-filter');

            cards.forEach(card => {
                const cardRarity = card.getAttribute('data-rarete');
                if (filterValue === 'all' || cardRarity === filterValue) {
                    card.style.display = 'inline-block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

const wrapper = document.querySelector('.booster-wrapper');
if (wrapper) {
    wrapper.addEventListener('click', () => {
        wrapper.classList.add('open');
        
        fetch('api_booster.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    data.cartes.forEach((item, index) => {
                        let textElem = document.getElementById(`card-text-${index+1}`);
                        let nameElem = document.getElementById(`card-name-${index+1}`);
                        let rarityElem = document.getElementById(`card-rarity-${index+1}`);
                        let imgElem = document.getElementById(`card-img-${index+1}`);
                        let cardContainer = document.getElementById(`elden-card-${index+1}`);

                        if (textElem) textElem.textContent = item.description;
                        if (nameElem) nameElem.textContent = item.nom;
                        if (rarityElem) {
                            rarityElem.textContent = item.rarete;
                            if (cardContainer) {
                                cardContainer.className = "elden-card " + item.rarete.toLowerCase();
                            }
                            rarityElem.className = "card-rarity " + item.rarete.toLowerCase();
                        }
                        if (imgElem) {
                            imgElem.src = item.image;
                            imgElem.style.display = 'block';
                        }
                    });
                }
            })
            .catch(err => console.error("Erreur API:", err));
    });
}
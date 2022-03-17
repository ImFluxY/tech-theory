<?php
    // inscription.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil - TechTheory</title>
    <meta name="viewport" content="width=device-width,initialscale=1.0,shrink-to-fit=no" />
    <meta name="description"
        content="Site de TechTheory regroupant une multitude de podcasts sur l'high-tech et les nouvelles technologies">
    <link rel="stylesheet" href="css/global-style.css" type="text/css" />
    <link rel="stylesheet" href="css/profil-style.css" type="text/css" />
    <link rel="icon" href="images/favicon.svg">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="css/menu.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <script src="js/boutonsFiltres.js"></script>
</head>

<body>

    <!----- HEADER ----->

    <div id="accueil-section"></div>
    <?php include("menu.php"); ?>

    <!----- MAIN ----->

    <main class="index">
        <section class="section-accueil">
            <div class="container-accueil">
                <div class="text-accueil">
                    <h1 class="titre-accueil">Bienvenue sur TechTheory !
                    </h1>
                    <p class="explication-accueil">Nous sommes un site proposant une multitude de podcasts
                        sur l’high-tech et les nouvelles technologies. Si vous êtes à la
                        recherche de nouveautés, novices ou experts de ce monde,
                        ou juste curieux, alors TechTheory est fait pour vous.
                    </p>
                    <p class="accroche-accueil">Rentrez dans notre monde !</p>
                </div>

                <img class="img-accueil" src="images/image-accueil.png" alt="Image isométrique de la page d'accueil">

            </div>
            <div class="btn-discover">
                <button class="discover"><a href="#discover">Découvrir</a></button>
            </div>
        </section>
        <div class="svg-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" id="discover">
            <path fill="#8C50FF" fill-opacity="1"
                d="M0,96L120,106.7C240,117,480,139,720,128C960,117,1200,75,1320,53.3L1440,32L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z">
            </path>
        </svg>
        </div>
        <section class="section-slider-podcast">
            <div class="container-description-site">
                <div class="description-site">
                    <img class="img-description" src="images/database-icon2.png"
                        alt="icône representant le choix des podcasts">

                    <p class="p-description">De nombreux choix de podcasts</p>
                </div>
                <div class="description-site">
                    <img class="img-description" src="images/filter-icon2.png"
                        alt="icône representant le filtrage des podcasts">

                    <p class="p-description">Filtrer les podcasts en fonction de votre goût</p>
                </div>
                <div class="description-site">

                    <img class="img-description" src="images/follow-icon2.png"
                        alt="icône representant le suivi des créateurs">
                    <p class="p-description">Suivez vos créateurs préférés</p>
                </div>
            </div>


            <h2 class="h2-sec-podcast" id="podcasts">Podcasts</h2>

            <!------ NOUVEAUTÉS ----->

            <h3 class="h3-slider">Nouveautés</h3>
            <div class="container-pod">
            <?php
                // Connexion au serveur de base de données
                $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                $bdd->query("SET NAMES utf8");
                $bdd->query("SET CHARACTER SET 'utf8'");

                // Envoi de la requête SQL au serveur
                $requete = "SELECT p.idPodcast, p.titrePodcast, p.imgPodcast, p.cheminPodcast, p.moyenneNotePodcast, c1.nomFiltre, c2.idCreateur , c2.nomCreateur, c2.nomEmission
                FROM PODCAST p
                INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                INNER JOIN CATEGORIES c1 ON c1.idFiltre = fp.idFiltre
                INNER JOIN A_FAIT af ON af.idPodcast = p.idPodcast
                INNER JOIN CREATEUR c2 ON c2.idCreateur = af.idCreateur
                WHERE c1.nomFiltre LIKE '%Nouveautés%';";
                $statement = $bdd->query($requete);

                // Affiche données retournées
                $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                
                while ($ligne != false) {

                    if(isset($_SESSION['idUser'])){
                        $idUser = $_SESSION['idUser'];
                        $idPodcast = $ligne['idPodcast'];

                        $ecouterqt = "SELECT *
                        FROM ECOUTE e
                        WHERE e.idUser = $idUser
                        AND e.idPodcast = $idPodcast";
                        $ecoutestatement = $bdd->query($ecouterqt);
                        // Affiche données retournées
                        $aecoute = $ecoutestatement->fetch(PDO::FETCH_ASSOC);

                        $ecoute = true;
                        if(empty($aecoute))
                        {
                           $ecoute = false;
                        }
                    }
            ?>
                <div class="box-pod" data-id="<?php echo($ligne['idPodcast']); ?>" data-podcast="<?php echo($ligne['cheminPodcast']); ?>" data-createur="<?php echo($ligne['nomEmission']); ?>" data-pagecreateur="createur.php?id=<?php echo($ligne['idCreateur']); ?>" 
                    data-titre="<?php echo($ligne['titrePodcast']); ?>" data-image="<?php echo($ligne['imgPodcast']); ?>" data-pagepodcast="podcasts.php?id=<?php echo($ligne['idPodcast']); ?>" data-vu="<?php echo($ecoute); ?>">
                    <div class="img-pod-container">
                        <img src=<?php echo($ligne['imgPodcast']); ?> alt="image du podcast" class="img-pod">
                    </div>
                    <div class="details-pod">
                        <div class="look-podcast">
                            <i class="violet check-degage fas fa-check"></i>
                            <p class="p-look">Écouté</p>
                        </div>
                        <div class="content-pod">
                            <img src="images/play-icon.png" alt="icône bouton play" class="img-play-pod lecture-audio" title="Lire ce podcast">
                            <h4 class="h4-details-pod"><a href="podcast.php?id=<?php echo($ligne['idPodcast']); ?>" class="a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['titrePodcast']); ?></a></h4>
                            <h4 class="h4-details-pod"><a href="createur.php?id=<?php echo($ligne['idCreateur']); ?>" class="a-details-pod2 a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['nomEmission']); ?></a></h4>
                            <p class="p-details-pod" title="Note moyenne du podcast"><?php echo(round($ligne['moyenneNotePodcast'], 1)); echo(" / 5"); ?> · <?php echo($ligne['nomFiltre']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }

                    // Fermer la connexion au serveur de base de données
                    $pdo = null;
                ?>
            </div>

            <!----- TENDANCES ----->

            <h3 class="h3-slider">Tendances</h3>
            <div class="container-pod">
            <?php
                // Connexion au serveur de base de données
                $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                $bdd->query("SET NAMES utf8");
                $bdd->query("SET CHARACTER SET 'utf8'");

                // Envoi de la requête SQL au serveur
                $requete = "SELECT p.idPodcast, p.titrePodcast, p.imgPodcast, p.cheminPodcast, p.moyenneNotePodcast, p.vusPodcast , c1.nomFiltre, c2.idCreateur , c2.nomCreateur, c2.nomEmission
                FROM PODCAST p
                INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                INNER JOIN CATEGORIES c1 ON c1.idFiltre = fp.idFiltre
                INNER JOIN A_FAIT af ON af.idPodcast = p.idPodcast
                INNER JOIN CREATEUR c2 ON c2.idCreateur = af.idCreateur
                GROUP BY p.idPodcast
                ORDER BY p.vusPodcast DESC
                LIMIT 4;";
                $statement = $bdd->query($requete);

                // Affiche données retournées
                $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                
                while ($ligne != false) {

                    if(isset($_SESSION['idUser'])){
                        $idUser = $_SESSION['idUser'];
                        $idPodcast = $ligne['idPodcast'];

                        $ecouterqt = "SELECT *
                        FROM ECOUTE e
                        WHERE e.idUser = $idUser
                        AND e.idPodcast = $idPodcast";
                        $ecoutestatement = $bdd->query($ecouterqt);
                        // Affiche données retournées
                        $aecoute = $ecoutestatement->fetch(PDO::FETCH_ASSOC);

                        $ecoute = true;
                        if(empty($aecoute))
                        {
                           $ecoute = false;
                        }
                    }
            ?>
                <div class="box-pod" data-id="<?php echo($ligne['idPodcast']); ?>" data-podcast="<?php echo($ligne['cheminPodcast']); ?>" data-createur="<?php echo($ligne['nomEmission']); ?>" data-pagecreateur="createur.php?id=<?php echo($ligne['idCreateur']); ?>" 
                    data-titre="<?php echo($ligne['titrePodcast']); ?>" data-image="<?php echo($ligne['imgPodcast']); ?>" data-pagepodcast="podcasts.php?id=<?php echo($ligne['idPodcast']); ?>" data-vu="<?php echo($ecoute); ?>">
                    <div class="img-pod-container">
                        <img src=<?php echo($ligne['imgPodcast']); ?> alt="image du podcast" class="img-pod">
                    </div>
                    <div class="details-pod">
                        <div class="look-podcast">
                            <i class="violet check-degage fas fa-check"></i>
                            <p class="p-look">Écouté</p>
                        </div>
                        <div class="content-pod">
                            <img src="images/play-icon.png" alt="icône bouton play" class="img-play-pod lecture-audio" title="Lire ce podcast">
                            <h4 class="h4-details-pod"><a href="podcast.php?id=<?php echo($ligne['idPodcast']); ?>" class="a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['titrePodcast']); ?></a></h4>
                            <h4 class="h4-details-pod"><a href="createur.php?id=<?php echo($ligne['idCreateur']); ?>" class="a-details-pod2 a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['nomEmission']); ?></a></h4>
                            <p class="p-details-pod" title="Note moyenne du podcast"><?php echo(round($ligne['moyenneNotePodcast'], 1)); echo(" / 5"); ?> · <?php echo($ligne['nomFiltre']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }

                    // Fermer la connexion au serveur de base de données
                    $pdo = null;
                ?>
            </div>

            <!----- RECOMMANDATIONS ----->

            <h3 class="h3-slider">Recommandations</h3>
            <div class="container-pod">        
            <?php
                // Connexion au serveur de base de données
                $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                $bdd->query("SET NAMES utf8");
                $bdd->query("SET CHARACTER SET 'utf8'");

                $recommandation = "Recommandations";

                if(isset($_SESSION['idUser'])){

                    $idUser = $_SESSION['idUser'];
                    $rqt = "SELECT c.nomFiltre
                    FROM CATEGORIES c
                    INNER JOIN EST_INTERRESSE ei ON ei.idFiltre = c.idFiltre
                    WHERE ei.idUser = $idUser";
                    $stment = $bdd->query($rqt);
                    $recom = $stment->fetch(PDO::FETCH_ASSOC);

                    $recommandation = $recom['nomFiltre'];
                }

                // Envoi de la requête SQL au serveur
                $requete = "SELECT p.idPodcast, p.titrePodcast, p.imgPodcast, p.cheminPodcast, p.moyenneNotePodcast, c1.nomFiltre, c2.idCreateur , c2.nomCreateur, c2.nomEmission
                FROM PODCAST p
                INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                INNER JOIN CATEGORIES c1 ON c1.idFiltre = fp.idFiltre
                INNER JOIN A_FAIT af ON af.idPodcast = p.idPodcast
                INNER JOIN CREATEUR c2 ON c2.idCreateur = af.idCreateur
                WHERE c1.nomFiltre LIKE '%$recommandation%'
                LIMIT 4;";
                $statement = $bdd->query($requete);

                // Affiche données retournées
                $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                while ($ligne != false) {

                    if(isset($_SESSION['idUser'])){
                        $idUser = $_SESSION['idUser'];
                        $idPodcast = $ligne['idPodcast'];

                        $ecouterqt = "SELECT *
                        FROM ECOUTE e
                        WHERE e.idUser = $idUser
                        AND e.idPodcast = $idPodcast";
                        $ecoutestatement = $bdd->query($ecouterqt);
                        // Affiche données retournées
                        $aecoute = $ecoutestatement->fetch(PDO::FETCH_ASSOC);
                  
                        $ecoute = true;
                        if(empty($aecoute))
                        {
                           $ecoute = false;
                        }
                    }
            ?>
                <div class="box-pod" data-id="<?php echo($ligne['idPodcast']); ?>" data-podcast="<?php echo($ligne['cheminPodcast']); ?>" data-createur="<?php echo($ligne['nomEmission']); ?>" data-pagecreateur="createur.php?id=<?php echo($ligne['idCreateur']); ?>" 
                    data-titre="<?php echo($ligne['titrePodcast']); ?>" data-image="<?php echo($ligne['imgPodcast']); ?>" data-pagepodcast="podcast.php?id=<?php echo($ligne['idPodcast']); ?>" data-vu="<?php echo($ecoute); ?>">
                    <div class="img-pod-container">
                        <img src=<?php echo($ligne['imgPodcast']); ?> alt="image du podcast" class="img-pod">
                    </div>
                    <div class="details-pod">
                        <div class="look-podcast">
                            <i class="violet check-degage fas fa-check"></i>
                            <p class="p-look">Écouté</p>
                        </div>
                        <div class="content-pod">
                            <img src="images/play-icon.png" alt="icône bouton play" class="img-play-pod lecture-audio" title="Lire ce podcast">
                            <h4 class="h4-details-pod"><a href="podcast.php?id=<?php echo($ligne['idPodcast']); ?>" class="a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['titrePodcast']); ?></a></h4>
                            <h4 class="h4-details-pod"><a href="createur.php?id=<?php echo($ligne['idCreateur']); ?>" class="a-details-pod2 a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['nomEmission']); ?></a></h4>
                            <p class="p-details-pod" title="Note moyenne du podcast"><?php echo(round($ligne['moyenneNotePodcast'], 1)); echo(" / 5"); ?> · <?php echo($ligne['nomFiltre']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }

                    // Fermer la connexion au serveur de base de données
                    $pdo = null;
                ?>
            </div>
            <?php
                if(isset($_SESSION['idUser'])){
            ?>
            <div class="btn-creator-container">
                <a href="suivis.php" class="btn-creator">Voir les créateurs suivis</a>
            </div>
            <?php
                }
            ?>
        </section>

        <div class="svg-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#8C50FF" fill-opacity="1"
                d="M0,192L120,170.7C240,149,480,107,720,96C960,85,1200,107,1320,117.3L1440,128L1440,0L1320,0C1200,0,960,0,720,0C480,0,240,0,120,0L0,0Z">
            </path>
        </svg>
        </div>
        <section class="section-podcasts-grid" id="recherche">
            <h2 class="h2-grid-podcasts">Recherchez des podcasts</h2>
            <div class="box-container">
                <!--
                <div class="search-box">
                    <input type="text" name="search" class="search" placeholder="&#128269;  Recherchez votre podcast">
                </div>
                -->
                <form class="form-search" action="index.php#recherche" method="POST">
                    <input type="text" class="search texte-input-user" name="recherche" value="<?php isset($_SESSION['recherche']) ? $_SESSION['recherche'] : ""; ?>" placeholder="&#128269;  Recherchez votre podcast">
                    <input type="submit" class="input-search" name="rechercher" value="Recherchez"> 
                </form>
            </div>
            <div class="container-filter">
                <nav class="nav-element-filter">
                        <div class="element-items">
                            <span class="item item-select selectDefaut">Aucun Filtres</span>
                            <?php
                                // Connexion au serveur de base de données
                                $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                                $bdd->query("SET NAMES utf8");
                                $bdd->query("SET CHARACTER SET 'utf8'");

                                // Envoi de la requête SQL au serveur
                                $requete = "SELECT nomFiltre, afficherFiltre FROM CATEGORIES ORDER BY nomFiltre ASC";
                                $statement = $bdd->query($requete);

                                // Affiche données retournées
                                $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                                while ($ligne != false) {
                                    if($ligne['afficherFiltre'] > 0){
                            ?>
                            <span class="item"><?php echo ($ligne["nomFiltre"]) ; ?></span>
                            <?php
                                    }
                                    $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                                }
        
                                // Fermer la connexion au serveur de base de données
                                $pdo = null;
                            ?>
                            <form action="index.php#recherche" class="form-filtre" method="post">
                                <input type="hidden" name="filtres-selec" class="filtres-selec">
                                <input type="submit" class="input-search"  value="Appliquer Filtre(s)" name="formfiltre" id="formfiltre">
                            </form>
                        </div>
                </nav>
                <div class="container-pod">
                
                <?php
                    // Logique de recherche
                    $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                    $bdd->query("SET NAMES utf8");
                    $bdd->query("SET CHARACTER SET 'utf8'");

                    $filtres = isset($_SESSION['filtres']) ? $_SESSION['filtres'] : array("");
                    if(isset($_POST['formfiltre']))
                    {
                        $filtres = explode(',', $_POST['filtres-selec']);
                        $_SESSION['filtres'] = $filtres;
                    }

                    $recherche = isset($_SESSION['recherche']) ? $_SESSION['recherche'] : '';
                    if(isset($_POST['rechercher']))
                    {
                        $recherche = isset($_POST['recherche']) ? $_POST['recherche'] : '';
                        $_SESSION = $_POST['recherche'];

                        // la requete mysql
                        $requete = "SELECT idPodcast, titrePodcast, descPodcast, imgPodcast, cheminPodcast
                        FROM PODCAST
                        WHERE titrePodcast LIKE '%$recherche%'";

                        $statement = $bdd->query($requete);

                        // Affiche données retournées
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }

                    foreach($filtres as $filtre){
                        $requete = "SELECT p.idPodcast, p.titrePodcast, p.imgPodcast, p.cheminPodcast, p.moyenneNotePodcast, c1.nomFiltre, c2.idCreateur , c2.nomCreateur, c2.nomEmission
                        FROM PODCAST p
                        INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                        INNER JOIN CATEGORIES c1 ON c1.idFiltre = fp.idFiltre
                        INNER JOIN A_FAIT af ON af.idPodcast = p.idPodcast
                        INNER JOIN CREATEUR c2 ON c2.idCreateur = af.idCreateur
                        WHERE c1.nomFiltre LIKE '%$filtre%'
                        AND p.titrePodcast LIKE '%$recherche%'
                        GROUP BY p.titrePodcast";
                        $statement = $bdd->query($requete);

                        // Affiche données retournées
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                        while($ligne != false) { 

                            if(isset($_SESSION['idUser'])){
                                $idUser = $_SESSION['idUser'];
                                $idPodcast = $ligne['idPodcast'];
        
                                $ecouterqt = "SELECT *
                                FROM ECOUTE e
                                WHERE e.idUser = $idUser
                                AND e.idPodcast = $idPodcast";
                                $ecoutestatement = $bdd->query($ecouterqt);
                                // Affiche données retournées
                                $aecoute = $ecoutestatement->fetch(PDO::FETCH_ASSOC);
                        
                                $ecoute = true;
                                if(empty($aecoute))
                                {
                                    $ecoute = false;
                                }
                            }
                            
                        ?>
                            <div class="box-pod" data-id="<?php echo($ligne['idPodcast']); ?>" data-podcast="<?php echo($ligne['cheminPodcast']); ?>" data-createur="<?php echo($ligne['nomEmission']); ?>" data-pagecreateur="createur.php?id=<?php echo($ligne['idCreateur']); ?>" 
                            data-titre="<?php echo($ligne['titrePodcast']); ?>" data-image="<?php echo($ligne['imgPodcast']); ?>" data-pagepodcast="podcast.php?id=<?php echo($ligne['idPodcast']); ?>" data-vu="<?php echo($ecoute); ?>">
                                <div class="img-pod-container">
                                    <img src=<?php echo($ligne['imgPodcast']); ?> alt="image du podcast" class="img-pod">
                                </div>
                                <div class="details-pod">
                                    <div class="look-podcast">
                                        <i class="violet check-degage fas fa-check"></i>
                                        <p class="p-look">Écouté</p>
                                    </div>
                                    <div class="content-pod">
                                        <img src="images/play-icon.png" alt="icône bouton play" class="img-play-pod lecture-audio" title="Lire ce podcast">
                                        <h4 class="h4-details-pod"><a href="podcast.php?id=<?php echo($ligne['idPodcast']); ?>" class="a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['titrePodcast']); ?></a></h4>
                                        <h4 class="h4-details-pod"><a href="createur.php?id=<?php echo($ligne['idCreateur']); ?>" class="a-details-pod2 a-details-pod btn-lien-page btn-lien-pause"><?php echo($ligne['nomEmission']); ?></a></h4>
                                        <p class="p-details-pod" title="Note moyenne du podcast"><?php echo(round($ligne['moyenneNotePodcast'], 1)); echo(" / 5"); ?> · <?php echo($ligne['nomFiltre']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);    
                        }
                    }
                ?>
                </div>
            </div>
        </section>
        <section class="section-contact" id="contact">
            <h2 class="h2-section-contact">Contactez-nous via notre mail, le formulaire ou via notre compte Instagram
            </h2>
            <div class="contact">
                <a href="mailto:contact.techtheory@gmail.com" target="_blank"
                    class="a-contact">contact.techtheory@gmail.com</a>
            </div>
            <div class="instagram">
                <a href="https://www.instagram.com/tech_theory_podcast/" target="_blank"><img src="images/logo-insta.png"
                        alt="icône instagram" class="img-instagram"></a>
            </div>
            <div class="container-form">
                <form action="formulaire.php" method="POST" class="form-contact">
                    <div class="nom-prenom">
                        <input class="input-form texte-input-user" type="text" name="nom" placeholder="Votre nom :" aria-label="nom"
                            required>
                        <input class="input-form texte-input-user" type="text" name="prenom" placeholder="Votre prénom : "
                            aria-label="prenom" required>
                    </div>
                    <div class="mail-objet">
                        <input class="input-form texte-input-user" type="email" name="mail" placeholder="Votre e-mail : "
                            aria-label="mail" required>
                        <input class="input-form texte-input-user" type="text" name="objet" placeholder="Objet du message : "
                            aria-label="objet" required>
                    </div>
                    <textarea class="textarea-form texte-input-user" name="message" placeholder="Votre message : " cols="30" rows="10"
                        required></textarea>
                    <button type="submit" name="submit-contact" class="submit btn-submit">ENVOYER</button>
                </form>
            </div>
        </section>
        <div class="container-hdp" title="Remonter en haut">
                <a href="#accueil-section" class="hdp-btn" ><i class="fa-2x fas fa-angle-up"></i></a>
        </div>
        
        <?php  include("barreLecture.php"); ?>
        <?php  include("popup.php"); ?>
    </main>

    <!----- FOOTER ----->

    <footer class="footer">
        <p class="p-footer">Copyright © 2021 TechTheory | Tous droits réservés | <a href="po-donnees.html" class="a-politique">Politique de protection des données</a></p>
    </footer>
</body>

</html>
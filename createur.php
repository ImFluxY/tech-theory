<?php
    // inscription.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page créateur du projet tutoré, TechTheory">
    <title>Page créateur</title>
    <link rel="stylesheet" href="css/global-style.css" type="text/css">
    <link rel="stylesheet" href="css/page-createur.css" type="text/css">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <script src="js/interaction-suivre.js"></script> 
</head>

<body>
    <?php include("menu.php"); ?>
    <main class="createurs">
        <div class="createur">
            <div class="createur2">
                <div class="ensemble-description">
                    <?php
                        // Connexion au serveur de base de données
                        $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                        $bdd->query("SET NAMES utf8");
                        $bdd->query("SET CHARACTER SET 'utf8'");

                        // Envoi de la requête SQL au serveur
                        $idCreateur = $_GET['id'];
                        $requete = "SELECT c.nomEmission, c.nomCreateur , c.descCreateur, c.imgEmission
                        FROM CREATEUR c
                        WHERE c.idCreateur = $idCreateur";
                        $statement = $bdd->query($requete);
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <img src=<?php echo($ligne['imgEmission']); ?> alt="Logo Tech café">
                    <div class="description">
                        <h1><?php echo($ligne['nomEmission']); ?></h1>
                        <h2><?php echo($ligne['nomCreateur']); ?></h2>
                        <p><?php echo($ligne['descCreateur']); ?></h2></p>
                        <?php
                            //Voir si on suit
                            if(isset($_SESSION['idUser'])){
                                $idUser = $_SESSION['idUser'];
                                $suivrerqt = "SELECT *
                                FROM SUIVRE s
                                WHERE s.idCreateur = '$idCreateur' AND s.idUser = '$idUser'";
                                $suivrestatement = $bdd->query($suivrerqt);
                                $suivreCreateur = $suivrestatement->fetch(PDO::FETCH_ASSOC);

                                $suivre = 1;

                                if($suivreCreateur == false)
                                {
                                    $suivre = 0;
                                }
                        ?>
                        <img id ="imgCoeur" src="images/coeur-rempli.png" alt="coeur rempli" class="heart-details" title="Ne plus aimer le créateur"  data-aimer="<?php echo($suivre); ?>" data-createur="<?php echo($idCreateur); ?>">
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="container-podcast-crea">
                <div class="podcasts">
                    <h2>Podcasts</h2>
                    <?php
                        // Connexion au serveur de base de données
                        $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                        $bdd->query("SET NAMES utf8");
                        $bdd->query("SET CHARACTER SET 'utf8'");

                        // Envoi de la requête SQL au serveur
                        $id = $_GET['id'];
                        $requete = "SELECT p.idPodcast, p.titrePodcast, p.imgPodcast, p.cheminPodcast, c1.nomFiltre, c2.nomEmission, c2.idCreateur
                        FROM PODCAST p
                        INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                        INNER JOIN CATEGORIES c1 ON c1.idFiltre = fp.idFiltre
                        INNER JOIN A_FAIT af ON af.idPodcast = p.idPodcast
                        INNER JOIN CREATEUR c2 ON c2.idCreateur = af.idCreateur
                        WHERE c2.idCreateur = $id
                        GROUP BY p.titrePodcast;";
                        $statement = $bdd->query($requete);
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    
                        while ($ligne != false) {
                    ?>
                    <div class="podcast-propose" data-id="<?php echo($ligne['idPodcast']);?>" data-podcast="<?php echo($ligne['cheminPodcast']); ?>" data-createur="<?php echo($ligne['nomEmission']); ?>" data-pagecreateur="createur.php?id=<?php echo($ligne['idCreateur']); ?>" 
                    data-titre="<?php echo($ligne['titrePodcast']); ?>" data-image="<?php echo($ligne['imgPodcast']); ?>" data-pagepodcast="podcast.php?id=<?php echo($ligne['idPodcast']); ?>">
                        <img src=<?php echo($ligne['imgPodcast']); ?> alt="Logo Créateur">
                        <div class="podcast-propose-description">
                            <div class="btn-play">
                                <a href="podcast.php?id=<?php echo($ligne['idPodcast']); ?>"><h3><?php echo($ligne['titrePodcast']); ?></h3></a><img src="images/play-icon.png" class="lecture-audio">
                            </div>
                            <p><?php echo($ligne['nomEmission']); ?> · <?php echo($ligne['nomFiltre']); ?></p>
                        </div>
                    </div>
                    <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }

                    // Fermer la connexion au serveur de base de données
                    $pdo = null;
                    ?>
                </div>
                </div>
            </div>
        </div>
        <?php include("barreLecture.php"); ?>
        <?php include("popup.php"); ?>
    </main>
</body></html>
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
    <link rel="stylesheet" href="css/profil-style.css" type="text/css" />
    <link rel="stylesheet" href="css/global-style.css" type="text/css" />
    <link rel="icon" href="images/favicon.svg">
    <link rel="stylesheet" href="css/details-podcasts.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <script src="js/interaction-suivre.js"></script>
    <script src="js/interaction-note.js"></script>
</head>

<body>

    <?php include("menu.php"); ?>

    <!----- MAIN ----->

    <main class="details">
        <div class="container-details">
            <?php

                if(isset($_GET['id']) AND !empty($_GET['id'])){

                    $id = $_GET['id'];

                    // Connexion au serveur de base de données
                    $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                    $bdd->query("SET NAMES utf8");
                    $bdd->query("SET CHARACTER SET 'utf8'");
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Infos podcast
                    $podcastrqt = "SELECT p.idPodcast, p.titrePodcast, p.descPodcast, p.imgPodcast, p.datePodcast, p.cheminPodcast, p.moyenneNotePodcast, c.nomFiltre
                    FROM PODCAST p
                    INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                    INNER JOIN CATEGORIES c ON c.idFiltre = fp.idFiltre
                    WHERE p.idPodcast = $id
                    GROUP BY p.titrePodcast;";
                    $podcaststatement = $bdd->query($podcastrqt);
                    $podcast = $podcaststatement->fetch(PDO::FETCH_ASSOC);

                    // Infos créateur
                    $createurrqt = "SELECT c.idCreateur, c.nomCreateur, c.nomEmission, c.descCreateur
                    FROM CREATEUR c
                    INNER JOIN A_FAIT af ON af.idCreateur = c.idCreateur
                    INNER JOIN PODCAST p ON p.idPodcast = af.idPodcast
                    WHERE p.idPodcast = $id;";
                    $createurstatement = $bdd->query($createurrqt);
                    $createur = $createurstatement->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="wrapper-details" data-id="<?php echo($podcast['idPodcast']);?>" data-podcast="<?php echo($podcast['cheminPodcast']); ?>" data-createur="<?php echo($createur['nomEmission']); ?>" data-pagecreateur="createur.php?id=<?php echo($createur['idCreateur']); ?>" 
            data-titre="<?php echo($podcast['titrePodcast']); ?>" data-image="<?php echo($podcast['imgPodcast']); ?>" data-pagepodcast="podcast.php?id=<?php echo($podcast['idPodcast']); ?>">
                
                <div class="left-part-details">
                    <img src=<?php echo($podcast['imgPodcast']); ?> alt="image du podcast" class="img-pod-click">
                </div>
                <div class="right-part-details">
                    <div class="titre-principal">
                        <h2 class="h2-details-pod"><?php echo($podcast['titrePodcast']); ?></h2>
                        <img src="images/play-icon.png" alt="icône play" class="play-icon-titre lecture-audio" title="Lire le podcast">
                    </div>
                    <div class="infos-details">
                        <p class="infos-pod">Note moyenne : <?php echo($podcast['moyenneNotePodcast']); echo(" / 5"); ?> · <?php echo($podcast['datePodcast']); ?> · <?php echo($podcast['nomFiltre']); ?> · <a
                                href="createur.php?id=<?php echo($createur['idCreateur']); ?>" class="a-pod-details"><?php echo($createur['nomEmission']); ?></a></p>
                        <?php
                        if(isset($_SESSION['idUser']))
                        {
                            //Voir si on suit
                            $idCreateur = $createur['idCreateur'];
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
                        <?php } ?>
                    </div>
                    <p class="desc-pod"><?php echo($podcast['descPodcast']); ?></p>
                        <?php
                        
                        if(isset($_SESSION['idUser']))
                        {
                            $idUser = $_SESSION['idUser'];

                            // Infos podcast
                            $podcastrqt = "SELECT p.idPodcast, p.titrePodcast, p.descPodcast, p.imgPodcast, p.datePodcast, p.cheminPodcast, c.nomFiltre
                            FROM PODCAST p
                            INNER JOIN FAIT_PARTI fp ON fp.idPodcast = p.idPodcast
                            INNER JOIN CATEGORIES c ON c.idFiltre = fp.idFiltre
                            WHERE p.idPodcast = $id
                            GROUP BY p.titrePodcast;";
                            $podcaststatement = $bdd->query($podcastrqt);
                            $podcast = $podcaststatement->fetch(PDO::FETCH_ASSOC);

                            // Déja commenté
                            $dejacommenterqt = "SELECT c.idUser, p.idPodcast
                            FROM COMMENTAIRE c
                            INNER JOIN PODCAST p ON p.idPodcast = c.idPodcast
                            WHERE p.idPodcast = $id AND c.idUser = $idUser;";
                            $dejacommentestmnt = $bdd->query($dejacommenterqt);
                            $dejacommente = $dejacommentestmnt->fetch(PDO::FETCH_ASSOC);

                            if(isset($_POST['comment']) AND !empty($_POST['comment']) AND empty($dejacommente))
                            {
                                    // Envoi de la requête pour ajouter un commentaire SQL au serveur
                                    $date = date('Y-m-d'); // Format yyyy-mm-dd
                                    $insertcom = $bdd->prepare("INSERT INTO COMMENTAIRE(contenuCommentaire, dateCommentaire, idUser, idPodcast) VALUES(?, ?, ?, ?)");
                                    $parametrescom = array($_POST['comment'], $date,$idUser, $_GET['id']);
                                    $insertcom->execute($parametrescom);

                                    // Envoie de la requête pour ajouter une note SQL au serveur
                                    $note = $_POST['note'];
                                    $insertnote = $bdd->prepare("INSERT INTO NOTE(idPodcast, idUser, notePodcast) VALUES(?, ?, ?)");
                                    $parametresnote = array($_GET['id'], $idUser, $note);
                                    $insertnote->execute($parametresnote);

                                    //Calcul de la nouvelle note moyenne du podcast
                                    $selectmoyenne = "SELECT SUM(n.notePodcast) AS totaleNotes, COUNT(n.idUser) AS totaleUsers
                                    FROM NOTE n
                                    WHERE n.idPodcast = $id;";
                                    $moyennestatement = $bdd->query($selectmoyenne);
                                    $moyenne = $moyennestatement->fetch(PDO::FETCH_ASSOC);

                                    // Envoie de la nouvelle moyenne
                                    $nouvellemoyenne = $moyenne['totaleNotes'] / $moyenne['totaleUsers'];
                                    //var_dump($nouvellemoyenne);
                                    $updatemoyenne = "UPDATE PODCAST SET moyenneNotePodcast = $nouvellemoyenne
                                    WHERE PODCAST.idPodcast = $id";
                                    $bdd->query($updatemoyenne);
                            }

                            // Infos commentaire posté
                            $composterqt = "SELECT c.idUser, c.idPodcast
                            FROM COMMENTAIRE c
                            WHERE c.idPodcast = $id
                            AND c.idUser = $idUser";
                            $compostestatement = $bdd->query($composterqt);
                            $com = $compostestatement->fetch(PDO::FETCH_ASSOC);

                            if(empty($com)){
                        ?>
                        <form action="" method="post">
                            <div class="zone-avis">
                                <textarea class="comment-zone texte-input-user" id="comment" name="comment" rows="1" cols="50"></textarea>
                                <button type="submit" class="btn-comment">Ajouter un commentaire</button>
                                <div class="stars userNote" data-note="4">
                                <input type="hidden" name="note" value="3" class="input-note">
                                <img class="img-stars user-star" src="images/etoile-vide.png" alt="étoile vide" title="Noter 1 étoile" data-index="1">
                                <img class="img-stars user-star" src="images/etoile-vide.png" alt="étoile vide" title="Noter 2 étoiles" data-index="2">
                                <img class="img-stars user-star" src="images/etoile-vide.png" alt="étoile vide" title="Noter 3 étoiles" data-index="3">
                                <img class="img-stars user-star" src="images/etoile-vide.png" alt="étoile vide" title="Noter 4 étoiles" data-index="4">
                                <img class="img-stars user-star" src="images/etoile-vide.png" alt="étoile vide" title="Noter 5 étoiles" data-index="5">
                                </div>
                            </div>
                        </form>
                        <?php
                            }
                        }
                        ?>
                    <div class="container-comment">

                        <?php

                            // Envoi de la requête SQL au serveur
                            $commentairerqt = "SELECT c.contenuCommentaire , c.dateCommentaire, c.idUser, u.pseudoUser, u.avatarUser, n.notePodcast
                            FROM COMMENTAIRE c
                            INNER JOIN PODCAST p ON p.idPodcast = c.idPodcast
                            INNER JOIN USER u ON u.idUser = c.idUser
                            INNER JOIN NOTE n on n.idPodcast = p.idPodcast AND n.idUser = u.idUser
                            WHERE p.idPodcast = $id
                            GROUP BY n.notePodcast
                            ORDER BY c.dateCommentaire DESC ;";
                            $statement = $bdd->query($commentairerqt);

                            // Affiche données retournées
                            $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                            while ($ligne != false) {
                        ?>
                        <div class="sec-comment">
                            <img class="img-avatar" alt="icône avatar" src=<?php echo($ligne['avatarUser']) ?>>
                            <div class="infos-comment">
                                <div class="infos-content">
                                    <p class="name-date-comment"><?php echo($ligne['pseudoUser']) ?> · <span class="date-color"><?php echo($ligne['dateCommentaire']) ?></span></p>
                                    <textarea class="person-comment-pod" cols="120" disabled><?php echo($ligne['contenuCommentaire']) ?></textarea>
                                </div>
                                <div class="stars-comment" title="Noté <?php echo($ligne['notePodcast']); ?> étoile(s)" data-note="<?php echo($ligne['notePodcast']); ?>">
                                    <img class="img-stars" src="images/etoile-vide.png" alt="étoile vide">
                                    <img class="img-stars" src="images/etoile-vide.png" alt="étoile vide">
                                    <img class="img-stars" src="images/etoile-vide.png" alt="étoile vide">
                                    <img class="img-stars" src="images/etoile-vide.png" alt="étoile vide">
                                    <img class="img-stars" src="images/etoile-vide.png" alt="étoile vide">
                                </div>
                            </div>
                        </div>
                        <?php
                            $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                            }

                            // Fermer la connexion au serveur de base de données
                            $pdo = null;
                            
                }
                else{
                    header("Location: index.php");
                }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include("barreLecture.php"); ?>
        <?php include("popup.php"); ?>
    </main>
    
</body>
</html>
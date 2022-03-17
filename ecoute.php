<?php
    // ecoute.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");

    //Connexion à la base de données
    $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
    $bdd->query("SET NAMES utf8");
    $bdd->query("SET CHARACTER SET 'utf8'");

    //Récupération des données
    if(isset($_SESSION['idUser']) AND isset($_POST['idPodcast']))
    {
        $idUser = $_SESSION['idUser'];
        $idPodcast = $_POST['idPodcast'];

        //Vérification qu'il n'éxiste pas de lien
        $requete = "SELECT *
                FROM ECOUTE e
                WHERE e.idUser = $idUser AND e.idPodcast = $idPodcast;";

        $statement = $bdd->query($requete);
        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

        if($ligne == false)
        {
            //Insertion des données
            $insertecoute = $bdd->prepare("INSERT INTO ECOUTE(idUser, idPodcast) VALUES(?, ?)");
            $parametresecoute = array($idUser, $idPodcast);
            $insertecoute->execute($parametresecoute);
        }
    }
?>
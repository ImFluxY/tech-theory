<?php
    // ecoute.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");

    //Connexion à la base de données
    $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
    $bdd->query("SET NAMES utf8");
    $bdd->query("SET CHARACTER SET 'utf8'");

    //Récupération des données
    if(isset($_POST['idPodcast']))
    {
        $idPodcast = $_POST['idPodcast'];

        //Vérification qu'il n'éxiste pas de lien
        $requete = "SELECT p.vusPodcast
                FROM PODCAST p
                WHERE p.idPodcast = $idPodcast;";

        $statement = $bdd->query($requete);
        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

        $nouvelleValeur = $ligne['vusPodcast'] + 1;

        //Insertion des données
        $insertvu = $bdd->prepare("UPDATE PODCAST SET vusPodcast = ? WHERE idPodcast = $idPodcast");
        $parametresvu = array($nouvelleValeur);
        $insertvu->execute($parametresvu);
    }
?>
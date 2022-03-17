"use strict";
(function() {

    document.addEventListener("DOMContentLoaded", initialiser);
    let mainPage, basPage, blocBarreLecture;
    let btnCouperSon, nouveauVolume;
    let volumeSonoreRecent = 80,
        etatMute = "faux";
    let precedant, play, suivant;
    let titre, createur, image, son;
    let index;
    let timer, temps, timerAudio, timerTotal, positionAudio, dureeAudio, positionTimer, timerDonnes;
    let lesBtnLancerLecture;
    let podcastCliquer, podcastSuivant, podcastPrecedant, podcastActif, idPodcast, lesPodcasts;
    let btnLirePodcast = null;
    let nouveauPodcast = false;
    let lesLiensPause;
    let croixFermer;
    let boutonHaut;
    let verifierEtat = false;
    let btnConnexion;
    let limite;
    let zonesEcritures;
    let compteurPopup;
    let blocDejaVu;
    let compteurPodcastVu;

    function initialiser(evt) {
        mainPage = document.querySelector('main');
        basPage = document.querySelector('body');
        blocBarreLecture = document.querySelector('.barreLecture');
        image = document.querySelector('.imagePodcast');
        titre = document.querySelector('.titre');
        createur = document.querySelector('.createurBarre');
        son = document.createElement('audio');
        btnCouperSon = document.querySelector('.iconVolume');
        nouveauVolume = document.querySelector('.volumeBarre');
        precedant = document.querySelector('.precedant');
        play = document.querySelector('.play');
        suivant = document.querySelector('.suivant');
        dureeAudio = document.querySelector('.dureeAudio');
        timerAudio = document.querySelector('.timerAudio');
        timerTotal = document.querySelector('.timerTotal');
        lesBtnLancerLecture = document.getElementsByClassName('lecture-audio');
        lesLiensPause = document.getElementsByClassName("btn-lien-pause");
        croixFermer = document.querySelector('.btn-croix');
        btnConnexion = document.querySelector('.nav-links-connect');
        zonesEcritures = document.querySelectorAll('.texte-input-user');
        if (mainPage.classList.contains('index')) {
            boutonHaut = document.querySelector('.container-hdp');
        }
        limite = false;
        compteurPopup = 0;
        compteurPodcastVu = 0;
        precedant.title = "Podcast precedant";
        suivant.title = "Podcast suivant";
        initilisalisationPodcastDejaVu();
        if (sessionStorage.getItem("lectureActive")) {
            preparerLectureSon();
        }
        for (let unBtnLancerLecture of lesBtnLancerLecture) {
            unBtnLancerLecture.addEventListener('click', cliquerLancerPodcast);
        }
        play.addEventListener('click', mettrePlay);
        btnCouperSon.addEventListener('click', muterSon);
        nouveauVolume.addEventListener('change', changerVolume);
        precedant.addEventListener('click', mettreSonPrecedant);
        suivant.addEventListener('click', mettreSonSuivant);
        dureeAudio.addEventListener('change', changerTemps);
        for (let uneZoneEcriture of zonesEcritures){
            uneZoneEcriture.addEventListener('focus', ecrireCommentaire);
        }
    }

    function reinitialiserPosition() {
        dureeAudio.value = 0;
    }

    function preparerLectureSon() {
        initliasierParametresAudioRecuperee();
        mettrePlay();
        play.click();
    }

    function mettrePlay() {
        verifierEtat = true;
        if (!blocBarreLecture.classList.contains("lecture")) {
            afficherBarrelecture();
        }
        play.title = "Pause";
        sessionStorage.removeItem("pause");
        son.play();
        play.innerHTML = '<i class="fa fa-pause"></i>';
        play.classList.remove("inactive");
        play.removeEventListener('click', mettrePlay);
        play.addEventListener('click', mettrePause);
        if (sessionStorage.getItem("pause")) {
            mettrePause();
        }
    }

    function mettrePause() {
        verifierEtat = false;
        play.title = "Lecture";
        sessionStorage.setItem("pause", "vrai");
        son.pause();
        play.classList.add("inactive");
        play.innerHTML = '<i class="fa fa-play"></i>';
        play.addEventListener('click', mettrePlay);
        play.removeEventListener('click', mettrePause);
    }

    function cliquerLancerPodcast() {

        if (this != btnLirePodcast && btnLirePodcast != null) {
            cliquerArreterPodcast();
        }
        podcastCliquer = determinerParentPodcast(this);
        initliasierParametresNouvelAudio(podcastCliquer);

        //Ajouter un vu
        envoyerPodcastsEcouteServeur(podcastCliquer.dataset.id, "./vu.php");

        lesPodcasts = document.getElementsByClassName("box-pod");
        if (mainPage.classList.contains('index')) {
            for (let unPodcast of lesPodcasts) {
                if (unPodcast.dataset.id == podcastCliquer.dataset.id) {
                    btnLirePodcast = determinerEnfantPodcast(unPodcast);
                    transformerBoutonEnStop();
                    appliquerStylePodcastSelectionner(unPodcast);

                }
            }
        }else{
            btnLirePodcast = this;
            transformerBoutonEnStop();
            appliquerStylePodcastSelectionner(podcastCliquer);
        }
        compteurPodcastVu = 0;
        mettrePlay();
    }

    function transformerBoutonEnStop() {
        btnLirePodcast.src = "images/stop-icon.png";
        btnLirePodcast.alt = "icône bouton stop";
        btnLirePodcast.title = "Arrêter la lecture"
        btnLirePodcast.removeEventListener('click', cliquerLancerPodcast);
        btnLirePodcast.addEventListener('click', cliquerArreterPodcast);
        sessionStorage.setItem("podcastCliquer", btnLirePodcast);
    }

    function cliquerArreterPodcast() {
        lesPodcasts = document.getElementsByClassName("box-pod");
        if (mainPage.classList.contains('index')) {
            for (let unPodcast of lesPodcasts) {
                if (unPodcast.dataset.id == podcastCliquer.dataset.id) {
                    btnLirePodcast = determinerEnfantPodcast(unPodcast);
                    transformerBoutonEnPlay();
                    retirerStylePodcastSelectionner(unPodcast);
                }
            }
        }else{
            transformerBoutonEnPlay();
            retirerStylePodcastSelectionner(podcastCliquer);
        }
        terminerLecture();
        croixFermer.removeEventListener("click", terminerLecture);
    }

    function transformerBoutonEnPlay() {
        btnLirePodcast.src = "images/play-icon.png";
        btnLirePodcast.alt = "icône bouton play";
        btnLirePodcast.title = "Lire ce podcast";
        btnLirePodcast.addEventListener('click', cliquerLancerPodcast);
        btnLirePodcast.removeEventListener('click', cliquerArreterPodcast);
        sessionStorage.removeItem("podcastCliquer");
    }

    function afficherBarrelecture() {
        window.removeEventListener('unload', supprimerDonnes);
        sessionStorage.setItem("lectureActive", "vrai");
        blocBarreLecture.classList.add("lecture");
        basPage.style.marginBottom = "10vh";
        if (mainPage.classList.contains('index')) {
            boutonHaut.classList.add("top-active");
            croixFermer.style.display = "none";
        } else if (mainPage.classList.contains('details')) {
            croixFermer.style.display = "none";
            enleverPlaylist();
        } else if (mainPage.classList.contains('createurs')) {
            croixFermer.style.display = "none";
            if (podcastCliquer.nextElementSibling == null){
                enleverPlaylist();
            }
        } else {
            croixFermer.style.display = "block";
            enleverPlaylist();
        }
        croixFermer.addEventListener("click", terminerLecture);
        for (let unLienPause of lesLiensPause) {
            unLienPause.addEventListener("click", function(e) {
                e.preventDefault();
            });
        }
        window.addEventListener("keydown", desactiverTouchesBases);
        window.addEventListener('keydown', verifierToucheBarreLecture);
        window.addEventListener('unload', sauvegarderDonnes);
    }

    function enleverPlaylist() {
        precedant.style.opacity = "0.5";
        suivant.style.opacity = "0.5";
        precedant.style.cursor = "default";
        suivant.style.cursor = "default";
        precedant.title = "Pas de podcast precedant";
        suivant.title = "Pas de podcast suivant";
        precedant.removeEventListener('click', mettreSonPrecedant);
        suivant.removeEventListener('click', mettreSonSuivant);
    }

    function terminerLecture() {
        mettrePause();
        cacherBarrelecture();
    }

    function cacherBarrelecture() {
        window.removeEventListener('unload', sauvegarderDonnes);
        blocBarreLecture.classList.remove("lecture");
        basPage.style.marginBottom = "0";
        if (mainPage.classList.contains('index')) {
            boutonHaut.classList.remove("top-active");
        }
        for (let unBtnLancerLecture of lesBtnLancerLecture) {
            unBtnLancerLecture.addEventListener('click', mettrePlay);
        }
        index = 0;
        for (let unLienPause of lesLiensPause) {
            unLienPause.removeEventListener("click", function(e) {
                e.preventDefault();
            });
        }
        window.removeEventListener("keydown", desactiverTouchesBases);
        window.removeEventListener('keydown', verifierToucheBarreLecture);
        window.addEventListener('unload', supprimerDonnes);
    }

    function limiterLecture() {
        mettrePause();
        if (compteurPopup < 1){
            afficherPopupLimite();
        }
        play.removeEventListener('click', mettrePlay);
        play.addEventListener('click', afficherPopupLimite);
    }

    function afficherPopupLimite() {
        btnConnexion.classList.add("limite");
        compteurPopup = compteurPopup + 1;
    }

    function changerVolume() {
        son.volume = nouveauVolume.value / 100;
        volumeSonoreRecent = nouveauVolume.value;
        demuterSon();
    }

    function muterSon() {
        etatMute = "vrai";
        btnCouperSon.title = "Remettre le son";
        son.volume = 0;
        nouveauVolume.value = 0;
        btnCouperSon.classList.add("fa-volume-off");
        btnCouperSon.classList.remove("fa-volume-up");
        btnCouperSon.removeEventListener('click', muterSon);
        btnCouperSon.addEventListener('click', demuterSon);
    }

    function demuterSon() {
        etatMute = "faux";
        btnCouperSon.title = "Couper le son";
        son.volume = volumeSonoreRecent / 100;
        nouveauVolume.value = volumeSonoreRecent;
        btnCouperSon.classList.remove("fa-volume-off");
        btnCouperSon.classList.add("fa-volume-up");
        btnCouperSon.addEventListener('click', muterSon);
        btnCouperSon.removeEventListener('click', demuterSon);
    }

    function appliquerStylePodcastSelectionner(podcastSelect) {
        if (mainPage.classList.contains('index')) {
            podcastSelect.style.boxShadow = "5px 5px 7px rgb(13 13 13 / 45%)";
        }
        
    }

    function retirerStylePodcastSelectionner(podcastSelect) {
        if (mainPage.classList.contains('index')) {
            podcastSelect.style.boxShadow = "none";
        }
    }

    function mettreSonPrecedant() {
        cliquerArreterPodcast();
        if (podcastCliquer.previousElementSibling == null) {
            let parentListePod = podcastCliquer.parentElement;
            podcastPrecedant = parentListePod.lastElementChild;
        } else {
            podcastPrecedant = podcastCliquer.previousElementSibling;
        }
        btnLirePodcast = determinerEnfantPodcast(podcastPrecedant);
        initliasierParametresNouvelAudio(podcastPrecedant);
        btnLirePodcast.click();
        mettrePlay();
        podcastCliquer = podcastPrecedant;
    }

    function mettreSonSuivant() {
        cliquerArreterPodcast();
        if (podcastCliquer.nextElementSibling == null) {
            let parentListePod = podcastCliquer.parentElement;
            podcastSuivant = parentListePod.firstElementChild;
        } else {
            podcastSuivant = podcastCliquer.nextElementSibling;
        }
        btnLirePodcast = determinerEnfantPodcast(podcastSuivant);
        btnLirePodcast.click();
        mettrePlay();
        podcastCliquer = podcastSuivant;
    }

    function changerTemps() {
        positionAudio = son.duration * (dureeAudio.value / 100);
        son.currentTime = positionAudio;
        mettrePlay();
        if (btnConnexion!=null){
            if (dureeAudio.value > 33){
                compteurPopup = 0;
                limiterLecture();
                limite = true;
            }else{
                limite = false;
            }
        }
        if (dureeAudio.value > 65){
            if (compteurPodcastVu == 0){
                indiquerPodcastDejaVu(podcastCliquer);
                compteurPodcastVu = compteurPodcastVu + 1;
            } 
        }
    }

    function changerPositionTimer() {
        if (!isNaN(son.duration)) {
            positionTimer = (son.currentTime / son.duration) * 100;
            dureeAudio.value = positionTimer;
            affichagerTimerActuel();
            affichagerTimerTotal();
            if (btnConnexion!=null){
                if (positionTimer > 33){
                    limiterLecture();
                    limite = true;
                }
                else{
                    limite = false;
                }
            }
        }
        if (positionTimer > 65) {
            if (compteurPodcastVu == 0){
                indiquerPodcastDejaVu(podcastCliquer);
                compteurPodcastVu = compteurPodcastVu + 1;
            } 
        }
        if (son.duration == son.currentTime) {
            mettreSonSuivant();
        }
    }

    function affichagerTimerTotal() {
        var h = parseInt(((son.duration / 60) / 60) % 60);
        var m = parseInt((son.duration / 60) % 60);
        var s = parseInt(son.duration % 60);

        if (h == 0) {
            timerTotal.textContent = convertirTimer(m) + ":" + convertirTimer(s);
        } else {
            timerTotal.textContent = convertirTimer(h) + ":" + convertirTimer(m) + ":" + convertirTimer(s);
        }
    }

    function affichagerTimerActuel() {
        var h = parseInt(((son.currentTime / 60) / 60) % 60);
        var m = parseInt((son.currentTime / 60) % 60);
        var s = parseInt(son.currentTime % 60);

        if (h == 0) {
            timerAudio.textContent = convertirTimer(m) + ":" + convertirTimer(s);
        } else {
            timerAudio.textContent = convertirTimer(h) + ":" + convertirTimer(m) + ":" + convertirTimer(s);
        }
    }

    function convertirTimer(temps) {
        let tempsConverti;
        if (temps < 10) {
            tempsConverti = "0" + temps;
        } else {
            tempsConverti = temps;
        }
        return tempsConverti;
    }

    function determinerParentPodcast(btnLecture) {
        let parentLecture = btnLecture.parentElement;
        let grandParentBox = parentLecture.parentElement;
        podcastCliquer = grandParentBox.parentElement;
        return podcastCliquer;
    }

    function determinerEnfantPodcast(btnLecture) {
        let enfantLecture = btnLecture.lastElementChild;
        let petitEnfantBox = enfantLecture.lastElementChild;
        btnLirePodcast = petitEnfantBox.firstElementChild;
        if (mainPage.classList.contains('details')) {
            petitEnfantBox = enfantLecture.firstElementChild;
            btnLirePodcast = petitEnfantBox.lastElementChild;
        }
        if (mainPage.classList.contains('createurs')) {
            petitEnfantBox = enfantLecture.firstElementChild;
            btnLirePodcast = petitEnfantBox.lastElementChild;
        }
        return btnLirePodcast;
    }

    function initliasierParametresNouvelAudio(podcastActif) {
        son.src = podcastActif.dataset.podcast;
        image.src = podcastActif.dataset.image;
        titre.textContent = podcastActif.dataset.titre;
        titre.href = podcastActif.dataset.pagepodcast;
        createur.href = podcastActif.dataset.pagecreateur;
        createur.textContent = podcastActif.dataset.createur;
        idPodcast = podcastActif.dataset.id;
        clearInterval(timer);
        reinitialiserPosition();
        chargerAudio();
    }

    function initliasierParametresAudioRecuperee() {
        chargerAudio();
        son.src = sessionStorage.getItem("sourceSon");
        image.src = sessionStorage.getItem("sourceImage");
        titre.textContent = sessionStorage.getItem("sourceTitre");
        titre.href = sessionStorage.getItem("sourceLienPodcast");
        createur.href = sessionStorage.getItem("sourceLienCreateur");
        createur.textContent = sessionStorage.getItem("sourceNomCreateur");
        idPodcast = sessionStorage.getItem("sourceId");
        positionTimer = sessionStorage.getItem("positionTimer");
        timer = sessionStorage.getItem("timer");
        son.currentTime = sessionStorage.getItem("tempsRendu");
        etatMute = sessionStorage.getItem("etatMute");
        son.currentTime = sessionStorage.getItem("timerActuel");
        if (etatMute == "vrai") {
            muterSon();
        } else {
            demuterSon();
            son.volume = sessionStorage.getItem("niveauVolume");
            nouveauVolume.value = sessionStorage.getItem("positionSon");
        }
        if (mainPage.classList.contains('index')) {
            lesPodcasts = document.getElementsByClassName("box-pod");
            boutonHaut = document.querySelector('.container-hdp');
            for (let unPodcast of lesPodcasts) {
                if (unPodcast.dataset.id == idPodcast) {
                    podcastCliquer = unPodcast;
                    appliquerStylePodcastSelectionner(unPodcast);
                    btnLirePodcast = determinerEnfantPodcast(podcastCliquer);
                    transformerBoutonEnStop();
                }
            }
        } else if (mainPage.classList.contains('createurs')) {
            lesPodcasts = document.getElementsByClassName("podcast-propose");
            for (let unPodcast of lesPodcasts) {
                if (unPodcast.dataset.id == idPodcast) {
                    podcastCliquer = unPodcast;
                    appliquerStylePodcastSelectionner(unPodcast);
                    btnLirePodcast = determinerEnfantPodcast(podcastCliquer);
                    transformerBoutonEnStop();
                }
            }
        } else if (mainPage.classList.contains('details')) {
            let unPodcast = document.querySelector(".wrapper-details");
            if (unPodcast.dataset.id == idPodcast) {
                podcastCliquer = unPodcast;
                appliquerStylePodcastSelectionner(unPodcast);
                btnLirePodcast = determinerEnfantPodcast(podcastCliquer);
                transformerBoutonEnStop();
            }
        }
    }

    function chargerAudio() {
        timer = setInterval(changerPositionTimer, 400);
    }

    function sauvegarderDonnes() {
        sessionStorage.setItem("positionTimer", positionTimer);
        sessionStorage.setItem("timerActuel", son.currentTime);
        sessionStorage.setItem("timerTotal", son.duration);
        sessionStorage.setItem("positionSon", nouveauVolume.value);
        sessionStorage.setItem("niveauVolume", son.volume);
        sessionStorage.setItem("timer", timer);
        sessionStorage.setItem("sourceSon", son.src);
        sessionStorage.setItem("sourceImage", image.src);
        sessionStorage.setItem("sourceId", idPodcast);
        sessionStorage.setItem("sourceTitre", titre.textContent);
        sessionStorage.setItem("sourceLienPodcast", titre.href);
        sessionStorage.setItem("sourceLienCreateur", createur.href);
        sessionStorage.setItem("sourceNomCreateur", createur.textContent);
        sessionStorage.setItem("etatMute", etatMute);
        sessionStorage.setItem("btnLireactif", btnLirePodcast);
    }

    function supprimerDonnes() {
        sessionStorage.clear();
    }

    function desactiverTouchesBases(e) {
        if ([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
            e.preventDefault();
        }
    }

    function verifierToucheBarreLecture(event) {
        if (verifierEtat == false) {
            if (event.keyCode == 32) {
                if (limite == false){
                    mettrePlay();
                } else{
                    btnConnexion.classList.add("limite");
                }
            }
        } else {
            if (event.keyCode == 32) {
                mettrePause();
            }
        }
        if (event.keyCode == 37) {
            son.currentTime = son.currentTime - 5;
            positionTimer = positionTimer - 2;
        }
        if (event.keyCode == 39) {
            son.currentTime = son.currentTime + 5;
            positionTimer = positionTimer + 2;
        }
        if (event.keyCode == 38) {
            if (volumeSonoreRecent == 0) {
                demuterSon();
            }
            if (volumeSonoreRecent >= 80) {
                son.volume = 1;
                volumeSonoreRecent = 100;
                nouveauVolume.value = 100;
            } else {
                son.volume = son.volume + 0.20;
                volumeSonoreRecent = volumeSonoreRecent + 20;
                nouveauVolume.value = nouveauVolume.value + 20;
            }
            if (volumeSonoreRecent > 95) {
                son.volume = 1;
                volumeSonoreRecent = 100;
                nouveauVolume.value = 100;
            }
        }
        if (event.keyCode == 40) {
            if (volumeSonoreRecent <= 20) {
                son.volume = 0;
                volumeSonoreRecent = 0;
                nouveauVolume.value = 0;
            } else {
                son.volume = son.volume - 0.20;
                volumeSonoreRecent = volumeSonoreRecent - 20;
                nouveauVolume.value = nouveauVolume.value - 20;
            }
            if (volumeSonoreRecent < 5) {
                muterSon();
                son.volume = 0;
                volumeSonoreRecent = 0;
                nouveauVolume.value = 0;
            }
        }
    }

    function verifierParametres() {
        if (blocBarreLecture.classList.contains("lecture")) {
            supprimerDonnes();
        }
    }

    function ecrireCommentaire() {
        window.removeEventListener('keydown', verifierToucheBarreLecture);
        window.removeEventListener("keydown", desactiverTouchesBases);
        this.removeEventListener('focus', ecrireCommentaire);
        this.addEventListener('blur', remettreInteractionsApresEcritureCommentaire);
    }

    function remettreInteractionsApresEcritureCommentaire() {
        window.addEventListener('keydown', verifierToucheBarreLecture);
        window.addEventListener("keydown", desactiverTouchesBases);
        this.addEventListener('focus', ecrireCommentaire);
        this.removeEventListener('blur', remettreInteractionsApresEcritureCommentaire);
    }

    function indiquerPodcastDejaVu(podcastVu) {
        lesPodcasts = document.getElementsByClassName("box-pod");
        for (let unPodcast of lesPodcasts) {
            if (unPodcast.dataset.id == podcastVu.dataset.id) {
                let enfantPodcast = unPodcast.lastElementChild;
                blocDejaVu = enfantPodcast.firstElementChild;
                blocDejaVu.classList.add("deja-vu");
                envoyerPodcastsEcouteServeur(unPodcast.dataset.id, "./ecoute.php");
            }
        }
    }

    function initilisalisationPodcastDejaVu() {
        lesPodcasts = document.getElementsByClassName("box-pod");
        for (let unPodcast of lesPodcasts) {
            if (unPodcast.dataset.vu == "1") {
                let enfantPodcast = unPodcast.lastElementChild;
                blocDejaVu = enfantPodcast.firstElementChild;
                blocDejaVu.classList.add("deja-vu");
            }
        }
    }

    function envoyerPodcastsEcouteServeur(valeurIdPodcast, requeteUrl) {
            
        const requete = requeteUrl;
        const data = new FormData();
        data.append("idPodcast", valeurIdPodcast);

        const objetJson = { 
            method: 'POST',
            body: data
        };

        window.fetch(requete, objetJson)
    }

})();
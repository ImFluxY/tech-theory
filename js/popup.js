"use strict";
(function() {

    document.addEventListener("DOMContentLoaded", initialiser);
    let fenetrePopup;
    let assombrissementPopup;
    let btnAnnuler;
    let btnContinuer;
    let message;
    let btnLien;
    let caseAfficher;
    let blocBarreLecture;
    let imageTest;
    let page;
    let btnCiliquer;
    let btnPlay;
    let btnConnexion;
    let blockNePasAfficherMdp;
    let timerInterval;

    function initialiser(evt) {
        page = document.querySelector("main");
        fenetrePopup = document.querySelector(".container-popup");
        assombrissementPopup = document.querySelector(".assombrissementPopup");
        btnAnnuler = document.querySelector(".btn-popup");
        btnContinuer = document.querySelector(".btn-popup2");
        message = document.querySelector(".p-popup");
        btnLien = document.querySelectorAll(".btn-lien-page");
        caseAfficher = document.querySelector(".input-popup");
        blocBarreLecture = document.querySelector('.barreLecture');
        imageTest = document.querySelector('.img-accueil');
        btnPlay = document.querySelector('.play');
        btnConnexion = document.querySelector('.nav-links-connect');
        blockNePasAfficherMdp = document.querySelector('.sec-input-popup');

        let lesLiensPause = document.getElementsByClassName("btn-lien-pause");
        for (let unLienPause of lesLiensPause) {
            unLienPause.addEventListener("click", configurerPopupPause);
            unLienPause.addEventListener("click", arreterLienDefaut);
        }

        let lesLiensStop = document.getElementsByClassName("btn-lien-stop");
        for (let unLienStop of lesLiensStop) {
            unLienStop.addEventListener("click", configurerPopupStop);
            unLienStop.addEventListener("click", arreterLienDefaut);
        }
        if (btnConnexion!=null){
            if (btnConnexion.classList.contains("limite")){
                configurerPopupIdentification();
            }
        }
        timerInterval = setInterval(verfifierLimiteLecture, 400);
    }

    function verfifierLimiteLecture(evt) {
        if (btnConnexion!=null){
            if (btnConnexion.classList.contains("limite")){
                configurerPopupIdentification();
            }
        }
    }

    function configurerPopupPause(evt) {
        message.textContent = "En quittant cette page, la lecture du podcast en cours de lecture sera mise en pause.";
        message.style.marginBottom = "0";
        blockNePasAfficherMdp.style.display = "block";
        btnContinuer.href = this.href;
        btnCiliquer = this;
        sessionStorage.setItem("lectureActivePopup", "vrai");
        btnAnnuler.addEventListener("click", arreterLienDefaut);
        afficherPopup();
        verifierConditionsLecture();
    }

    function configurerPopupStop(evt) {
        message.textContent = "En quittant cette page, la lecture du podcast en cours de lecture prendra fin.";
        message.style.marginBottom = "0";
        blockNePasAfficherMdp.style.display = "block";
        btnAnnuler.textContent = "Annuler";
        btnContinuer.textContent = "Continuer";
        btnContinuer.href = this.href;
        sessionStorage.removeItem("lectureActivePopup");
        btnAnnuler.addEventListener("click", arreterLienDefaut);
        afficherPopup();
        verifierConditionsLecture();
    }

    function configurerPopupIdentification(evt) {
        message.textContent = "Vous ne pouvez pas écouter plus de 1/3 du podcast si vous n'êtes pas connecté ou inscrit.";
        message.style.marginBottom = "20px";
        blockNePasAfficherMdp.style.display = "none";
        btnAnnuler.textContent = "S'inscire";
        btnAnnuler.href = "inscription.php";
        btnContinuer.textContent = "Se connecter";
        btnContinuer.href = "connexion.php";
        btnAnnuler.removeEventListener("click", arreterLienDefaut);
        afficherPopup();
    }

    function afficherPopup(evt) {
        btnAnnuler.addEventListener("click", masquerPopup);
        btnContinuer.addEventListener("click", masquerPopup);
        caseAfficher.addEventListener("change", cocherCase);
        document.addEventListener('keydown', verifierTouche);
        assombrissementPopup.classList.add("visible");
        fenetrePopup.classList.add("visible");
    }

    function verifierConditionsLecture(evt) {
        if (sessionStorage.getItem("caseCoche")) {
            btnContinuer.click();
        } else if (blocBarreLecture.classList.contains("lecture")) {
            if (btnPlay.classList.contains("inactive")) {
                btnContinuer.click();
            } else {
                assombrissementPopup.classList.add("visible");
                fenetrePopup.classList.add("visible");
            }
        } else {
            btnContinuer.click();
        }
        if (page.classList.contains("index")) {
            if (btnCiliquer.classList.contains("a-img-techtheory")) {
                btnContinuer.click();
            }
            if (btnCiliquer.classList.contains("nav-links")) {
                btnContinuer.click();
            }
        }
    }

    function masquerPopup(evt) {
        assombrissementPopup.classList.remove("visible");
        fenetrePopup.classList.remove("visible");
        btnAnnuler.removeEventListener("click", masquerPopup);
        document.removeEventListener('keydown', verifierTouche);
    }

    function cocherCase(evt) {
        sessionStorage.setItem("caseCoche", "vrai");
        caseAfficher.removeEventListener("change", cocherCase);
        caseAfficher.addEventListener("change", decocherCase);
    }

    function decocherCase(evt) {
        sessionStorage.removeItem("caseCoche", "vrai");
        caseAfficher.addEventListener("change", cocherCase);
        caseAfficher.removeEventListener("change", decocherCase);
    }

    function verifierTouche(touche) {
        if (event.keyCode == 27) {
            masquerPopup();
            btnConnexion.classList.remove("limite");
        }
        if (event.keyCode == 13) {
            btnContinuer.click();
        }
    }

    function arreterLienDefaut(e) {
        e.preventDefault();
    }

})();
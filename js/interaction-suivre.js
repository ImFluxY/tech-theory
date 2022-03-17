"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);
	let coeur;
	let createur;

	function initialiser(evt) {
		coeur = document.querySelector(".heart-details");

		let aimer = coeur.dataset.aimer;
		createur = coeur.dataset.createur;

		if(aimer == 1)
		{
			coeur.src = "images/coeur-rempli.png";
			coeur.alt = "coeur plein";
			coeur.title = "Ne plus aimer le créateur";
	
			coeur.removeEventListener("click", aimerCreateur);
			coeur.addEventListener("click", nePlusAimerCreateur);
		}
		
		if(aimer == 0)
		{
			coeur.src = "images/coeur-vide.png";
			coeur.alt = "coeur vide";
			coeur.title = "Aimer le créateur";
	
			coeur.addEventListener("click", aimerCreateur);
			coeur.removeEventListener("click", nePlusAimerCreateur);
		}
	}

	function aimerCreateur(evt) {
		this.src = "images/coeur-rempli.png";
		this.alt = "coeur plein";
		this.title = "Ne plus aimer le créateur";

		coeur.removeEventListener("click", aimerCreateur);
		coeur.addEventListener("click", nePlusAimerCreateur);
        envoyerPodcastsEcouteServeur(1, createur);
	}

	function nePlusAimerCreateur(evt) {
		this.src = "images/coeur-vide.png";
		this.alt = "coeur vide";
		this.title = "Aimer le créateur";

		coeur.addEventListener("click", aimerCreateur);
		coeur.removeEventListener("click", nePlusAimerCreateur);
        envoyerPodcastsEcouteServeur(0, createur);
	}

    function envoyerPodcastsEcouteServeur(suivre, createur) {
            
        const requete = "./suivre.php";
        const data = new FormData();
        data.append("suivre", suivre);
		data.append("createur", createur);

        const objetJson = { 
            method: 'POST',
            body: data
        };

        window.fetch(requete, objetJson)
    }

})();
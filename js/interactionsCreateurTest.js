"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);

	function initialiser(evt) {
		
		let lesCoeurs = document.getElementsByClassName("heart-details");
		for(let unCoeur of lesCoeurs){
		unCoeur.addEventListener("click", nePlusAimerCreateur);
		
	}
	}

	function nePlusAimerCreateur(evt) {
		this.src = "images/coeur-vide.png";
		this.alt = "coeur vide";
		this.title = "Aimer le créateur";
		this.dataset.aimer = "0";

		let unChamp = this.previousElementSibling;
		unChamp.setAttribute('value', 0);
		this.removeEventListener("click", nePlusAimerCreateur);
		this.addEventListener("click", aimerCreateur);
	}

	function aimerCreateur(evt) {
		this.src = "images/coeur-rempli.png";
		this.alt = "coeur plein";
		this.title = "Ne plus aimer le créateur";
		this.dataset.aimer = "1";

		let lesChamps = document.getElementsByClassName("createur-suivre");

		for( let unChamp of lesChamps){
			unChamp.setAttribute('value', 1);
		}

		this.removeEventListener("click", aimerCreateur);
		this.addEventListener("click", nePlusAimerCreateur);
	}

})();






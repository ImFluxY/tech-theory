"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);

	function initialiser(evt) {
		
        let unCoeur = document.getElementById("imgCoeur");
		let unChamp = document.getElementById('btnCoeur');
		//unChamp.style.visibility = "hidden";

        if(unCoeur.src == "https://la-projets.univ-lemans.fr/~mmi1pj04/images/coeur-vide.png"){
            unCoeur.addEventListener("click", aimerCreateur);

        }else if(unCoeur.src == "https://la-projets.univ-lemans.fr/~mmi1pj04/images/coeur-rempli.png"){
            unCoeur.addEventListener("click", nePlusAimerCreateur);
        }
	}

	function nePlusAimerCreateur(evt) {
		this.src = "images/coeur-vide.png";
		this.alt = "coeur vide";
		this.title = "Aimer le créateur";
		this.dataset.aimer = "0";

		let unChamp = document.getElementById('btnCoeur');
		unChamp.setAttribute('value', 0);
		unChamp.click();
		/*
		function submitFormulaire (evt) {
			unChamp.click();
		}
		setTimeout(submitFormulaire, 3000);
		*/
		
		this.removeEventListener("click", nePlusAimerCreateur);
		this.addEventListener("click", aimerCreateur);
	}

	function aimerCreateur(evt) {
		this.src = "images/coeur-rempli.png";
		this.alt = "coeur plein";
		this.title = "Ne plus aimer le créateur";
		this.dataset.aimer = "1";

		let unChamp = document.getElementById('btnCoeur');
		unChamp.setAttribute('value', 1);
		unChamp.click();
		/*
		function submitFormulaire (evt) {
			unChamp.click();
		}
		setTimeout(submitFormulaire, 1000);
		*/
		
		
		
		
		
		this.removeEventListener("click", aimerCreateur);
		this.addEventListener("click", nePlusAimerCreateur);
	}

})();






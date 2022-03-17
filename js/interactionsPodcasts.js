"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);
	let lesEtoiles;
	let coeur;
	let indexEtoile;
	let parentNoteDonnee;

	function initialiser(evt) {
		parentNoteDonnee = document.querySelector(".userNote");
		afficherNotesCommentaires();
		appliquerNoteDonnee(parentNoteDonnee);
		coeur = document.querySelector(".heart-details");
		coeur.addEventListener("click", aimerCreateur);
		lesEtoiles = document.getElementsByClassName("user-star");
		for(let uneEtoile of lesEtoiles)
		{
			uneEtoile.addEventListener("click", attribuerNote);
		}	
	}

	function afficherNotesCommentaires(evt) {
		let lesCommentaires = document.getElementsByClassName("stars-comment");
		for(let unCommentaire of lesCommentaires)
    	{
			appliquerNoteDonnee(unCommentaire);
    	}
	}

	function appliquerNoteDonnee(noteGlobale) {
		let nbEtoile, etoileSuivante, etoileAttribuee, nbEtoileAppliquee;
		nbEtoile = noteGlobale.dataset.note;
		etoileAttribuee = noteGlobale.firstElementChild;
		if (nbEtoile > 0){
			etoileAttribuee.src = "images/etoile-pleine.png";
			etoileAttribuee.alt = "étoile pleine";
		}
		etoileSuivante = etoileAttribuee.nextElementSibling;
		for (nbEtoileAppliquee = 1; nbEtoileAppliquee < nbEtoile; nbEtoileAppliquee++) {
			etoileSuivante.src = "images/etoile-pleine.png";
			etoileAttribuee.alt = "étoile pleine";
			etoileSuivante = etoileSuivante.nextElementSibling;
		  }
	}

	function aimerCreateur(evt) {
		this.src = "images/coeur-rempli.png";
		this.alt = "coeur plein";
		this.title = "Ne plus aimer le créateur";

		let inputSuivre = document.querySelector(".createur-suivre");
		inputSuivre.setAttribute('value', 1);

		coeur.removeEventListener("click", aimerCreateur);
		coeur.addEventListener("click", nePlusAimerCreateur);
	}

	function nePlusAimerCreateur(evt) {
		this.src = "images/coeur-vide.png";
		this.alt = "coeur vide";
		this.title = "Aimer le créateur";
		let inputSuivre = document.querySelector(".createur-suivre");
		inputSuivre.setAttribute('value', 0);
		coeur.addEventListener("click", aimerCreateur);
		coeur.removeEventListener("click", nePlusAimerCreateur);
	}

	function attribuerNote(evt) {
		parentNoteDonnee = document.querySelector(".userNote");
		for(let uneEtoile of lesEtoiles)
    	{
        	uneEtoile.src = "images/etoile-vide.png";
			uneEtoile.alt = "étoile vide";
    	}
		let etoilePrecedante;
		indexEtoile = this.dataset.index;
		parentNoteDonnee.dataset.note = indexEtoile;
		this.src = "images/etoile-pleine.png";
		this.alt = "étoile pleine";
		etoilePrecedante = this.previousElementSibling;
		while (indexEtoile != 1) {
			etoilePrecedante.src = "images/etoile-pleine.png";
			etoilePrecedante.alt = "étoile pleine";
			etoilePrecedante = etoilePrecedante.previousElementSibling;
			indexEtoile = indexEtoile - 1;
		}
		let inputNote = document.querySelector(".input-note");
		let etoiles = document.querySelector(".stars");
		inputNote.value = etoiles.dataset.note;
	}

})();






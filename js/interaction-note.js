"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);
	let lesEtoiles;
	let indexEtoile;
	let parentNoteDonnee;

	function initialiser(evt) {
		parentNoteDonnee = document.querySelector(".userNote");
		afficherNotesCommentaires();
		appliquerNoteDonnee(parentNoteDonnee);
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






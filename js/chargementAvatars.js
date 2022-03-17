"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);
	var imageActive, lesAvatars, selecteurFichier, avatarChemin;

	function initialiser() {
		imageActive = document.querySelector(".img-personne");
		lesAvatars = document.getElementsByClassName("img-avatar");
		selecteurFichier = document.querySelector(".p-import");
		avatarChemin = document.querySelector('#avatarChemin');
		console.log(avatarChemin);
    	for(let unAvatar of lesAvatars)
    	{
        	unAvatar.addEventListener("click", changerEmplacementAvatar);
    	}
		selecteurFichier.addEventListener("click", deselectionnerAvatarsPredefinis);
	}

	function changerEmplacementAvatar() {
		deselectionnerAvatarsPredefinis();
		imageActive.src = this.dataset.image;
		avatarChemin.setAttribute('value', this.dataset.image);
		console.log(this.dataset.image);
		this.classList.add("select");
	}

	function deselectionnerAvatarsPredefinis() {
		for(let unAvatar of lesAvatars)
    	{
        	unAvatar.classList.remove("select");
    	}
	}

	

})();






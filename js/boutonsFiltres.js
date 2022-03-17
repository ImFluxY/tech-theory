"use strict";
(function () {

	document.addEventListener("DOMContentLoaded", initialiser);
	let filtreDefaut;
	let compteur = 0;

	function initialiser(evt) {
		filtreDefaut = document.querySelector(".selectDefaut");
		let lesBoutons = document.getElementsByClassName("item");
    	for(let unBouton of lesBoutons)
    	{
        	unBouton.addEventListener("click", selectionnerBouton);
    	}

		let form = document.querySelector(".form-filtre");
		form.addEventListener("click", filtresSelec);
	}

	function selectionnerBouton(evt) {
		filtreDefaut.classList.remove("item-select");
		this.classList.add("item-select");
		compteur = compteur + 1;
		this.removeEventListener("click", selectionnerBouton);
		this.addEventListener("click", deselectionnerBouton);
	}

	function deselectionnerBouton(evt) {
		this.classList.remove("item-select");
		compteur = compteur - 1;
		if (compteur == 0){
			filtreDefaut.classList.add("item-select");
		}
		this.addEventListener("click", selectionnerBouton);
		this.removeEventListener("click", deselectionnerBouton);		
	}

	function filtresSelec(evt)
	{
		let inputSelec = document.querySelector(".filtres-selec");
		inputSelec.setAttribute("value", "");

		let lesBoutons = document.getElementsByClassName("item-select");
		let i = 0;
		for(let unBouton of lesBoutons)
		{
			i++;

			if(unBouton.innerHTML != "Aucun Filtres"){
				inputSelec.setAttribute("value", inputSelec.value + unBouton.innerHTML);
			}

			if(i < lesBoutons.length)
			{
				inputSelec.setAttribute("value", inputSelec.value + ",");
			}
		}
	}

})();






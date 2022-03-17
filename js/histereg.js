"use strict";
(function() {

    document.addEventListener("DOMContentLoaded", initialiser);
    let son, entree;

    function initialiser(evt) {
        console.log("son charger");
        son = document.createElement('audio');
        entree = document.querySelector('input');
        son.src = "music/honteux.mp3";
        son.load;
        entree.addEventListener("click", joeurSon);
        console.log(entree);
    }

    function joeurSon() {
        console.log("test click ok");
        son.volume = 0.01;
        son.play();
    }

})();
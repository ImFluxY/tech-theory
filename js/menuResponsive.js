"use strict";
(function () {

    
    document.addEventListener("DOMContentLoaded", initialiser);
    var boutonMenuMobile;
    var interfaceMenuMobile;
    var assombrissement;
    
    function initialiser(evt) {
        boutonMenuMobile = document.querySelector(".logo-menu-mobile");
        assombrissement = document.querySelector(".assombrissement-site-menu");
        boutonMenuMobile.addEventListener("click", afficherMenu);
        document.addEventListener("keydown", cacherMenu);
        interfaceMenuMobile = document.querySelector(".module-mobile");
    }
    
    function afficherMenu(evt) {
        interfaceMenuMobile.classList.add("visible");
        assombrissement.classList.add("visible");
        boutonMenuMobile.removeEventListener("click", afficherMenu);
        assombrissement.addEventListener("click", cacherMenu);
        interfaceMenuMobile.addEventListener("click", cacherMenu);
    }
    
    function cacherMenu(evt) {
        interfaceMenuMobile.classList.remove("visible");
        assombrissement.classList.remove("visible");
        boutonMenuMobile.addEventListener("click", afficherMenu);
        assombrissement.removeEventListener("click", cacherMenu);
    }
    
})();

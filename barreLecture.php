<div class="barreLecture">
   <div class="contenuBarre">
      <div class="partieGauche">
         <img class="imagePodcast" src="">
         <div class="infosPodcast">
            <a href="" class="titre btn-lien-page btn-lien-pause" title="Voir les détails du podcast">title.mp3</a>
            <a href="" class="createurBarre btn-lien-page btn-lien-pause" title="Voir le créateur">Artist name</a>
         </div>
         <div class="volume">
            <i class="fa fa-volume-up iconVolume" title="Couper le son"></i>
            <input type="range" min="0" max="100" value="80" class="volumeBarre" title="Modifier le volume">  
         </div>
      </div>
      <div class="btn-multimedia">
         <div class="precedant btn-multi" title="Podcast précédant"><i class="fa fa-step-backward"></i></div>
         <div class="play btn-multi" title="Lecture"><i class="fa fa-play" aria-hidden="true"></i></div>
         <div class="suivant btn-multi" title="Podcast suivant"><i class="fa fa-step-forward"></i></div>
      </div>
      <div class="partieDroite">
         <div class="duree">
            <p class="timerAudio timerCode"></p>
            <input type="range" min="0" max="100" value="0" class="dureeAudio" title="Position du lecteur">
            <p class="timerTotal timerCode"></p>
         </div>
         <i class="fa fa-times btn-croix" title="Arrêter la lecture"></i>
      </div>
   </div>
</div>
<script src="js/barreLecture.js"></script>
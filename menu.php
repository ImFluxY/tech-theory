<script src="js/menuResponsive.js"></script>
<header class="header">
    <div class="nav-container">
        <a href="index.php#accueil" class="a-img-techtheory btn-lien-page btn-lien-pause"><img src="images/logo-techtheory.png"
            alt="logo du projet tutoré" class="img-techtheory"></a>
         <nav class="navbar">
            <ul class="nav-menu">
                <li><a href="index.php#accueil" class="nav-links btn-lien-page btn-lien-pause">Accueil</a></li>
                <li><a href="index.php#podcasts" class="nav-links btn-lien-page btn-lien-pause">Podcasts</a></li>
                <li><a href="index.php#contact" class="nav-links btn-lien-page btn-lien-pause">Contact</a></li>
                <?php
                    if(isset($_SESSION['idUser'])){
                ?>
                <div class="btn-compte-container">
                    <div class="img-compte">
                        <a href="profil.php" class="btn-lien-page btn-lien-pause"><img src="<?php echo($_SESSION['avatarUser']); ?>" alt="avatar du compte"
                                    class="img-avatar-nav"></a>
                    </div>
                    <li><a href="profil.php" class="btn-compte btn-lien-page btn-lien-pause"><?php echo($_SESSION['pseudoUser']); ?></a></li>
                </div>
                <?php 
                    }else{
                ?>
                <div class="btn-connect">
                    <li><a href="inscription.php" class="nav-links nav-links-btn-signin btn-lien-page btn-lien-stop">S'inscrire</a></li>
                    <li><a href="connexion.php" class="nav-links-connect nav-links-btn-connect btn-lien-page btn-lien-stop">Se connecter</a></li>
                </div>
                <?php 
                    }
                ?>
            </ul>
        </nav>
    </div>
    <div class="slidebar-menu-mobile">
    <div class="logo-menu-mobile">
        <div class="barre-mobile"></div>
        <div class="barre-mobile"></div>
        <div class="barre-mobile"></div>
    </div>
    <div class="module-mobile">
        <ul class="liste-menu-mobile">
            <li class="menu-bouton-mobile">
                <a href="index.php#accueil" class="menu-texte btn-lien-page btn-lien-pause">Accueil</a>
            </li>
            <li class="menu-bouton-mobile">
                <a href="index.php#podcasts" class="menu-texte btn-lien-page btn-lien-pause">Podcasts</a>
            </li>
            <li class="menu-bouton-mobile">
                <a href="index.php#contact" class="menu-texte btn-lien-page btn-lien-pause">Contact</a>
            </li>
            <?php
                if(isset($_SESSION['idUser'])){
            ?>
            <div class="btn-compte-container-mobile">
                 <div class="img-compte">
                    <a href="profil.php" class="btn-lien-page btn-lien-pause"><img src=<?php echo($_SESSION['avatarUser']); ?> alt="avatar du compte"
                        class="img-avatar-nav"></a>
                </div>
                <li><a href="profil.php" class="btn-compte-mobile btn-lien-page btn-lien-pause"><?php echo($_SESSION['pseudoUser']); ?></a></li>
            </div>
            <?php 
                }else{
            ?>
            <li class="menu-bouton-mobile"><a href="connexion.php" class="menu-texte menu-texte-connect btn-lien-page btn-lien-stop">Se connecter</a></li>
            <li class="menu-bouton-mobile"><a href="inscription.php" class="menu-texte menu-texte-connect btn-lien-page btn-lien-stop">S'inscrire</a></li>
            <?php 
                }
            ?>
        </ul>
    </div>
    <div class="assombrissement-site-menu"></div>
</header>
<header>
    
    <div id="logo"><!-- logo -->
        <img src="<?php echo $chemin; ?>img/imgAdmin/logoindia.png" alt="Logo Inde du sud" />
    </div><!-- fin logo -->
    
    <div id="formulaire_connexion"><!-- formulaire de connexion -->
        <?php
        echo $content;
        ?>
    </div><!-- fin formulaire de connexion -->
    
    <nav><!-- navigation -->
        <ul>
            <li class="<?php echo $position; ?>"><a href="<?php echo $chemin2; ?>index.php">ACCUEIL</a></li>
            <li class="<?php echo $position2; ?>"><a href="<?php echo $chemin; ?>article.php">INDE DU SUD</a></li>
            <li class="<?php echo $position3; ?>"><a href="<?php echo $chemin; ?>actualite.php">ACTUALITES INDIENNES</a></li>
            <li class="<?php echo $position4; ?>"><a href="<?php echo $chemin; ?>forum.php">FORUM</a></li>
            <li class="<?php echo $position5; ?>"><a href="<?php echo $chemin; ?>albumphoto.php">ALBUM PHOTOS</a></li>
            <li class="<?php echo $position6; ?>"><a href="<?php echo $chemin; ?>commentaire.php">COMMENTAIRES</a></li>
            <li class="<?php echo $position7; ?>"><a href="<?php echo $chemin; ?>inscription.php">INSCRIPTION</a></li>
        </ul>
    </nav><!-- fin navigation -->
    
</header>
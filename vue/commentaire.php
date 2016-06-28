<?php require_once ('../controleur/controlecommentaire.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/stylecommentaire.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundcommentaire">
                <section id="conteneur_commentaire">

                    <article class="cube">
                        <div id="cube1">
                            <div id="smileface"><span class="fa fa-smile-o"></span></div>
                            <div id="contenucube1"><p>UNE QUESTION ? UN COMMENTAIRE ? POSTEZ-LE ICI.</p></div>
                            <div id="flechecube1"><span class="fa fa-caret-right"></span></div>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="fondcube<?php echo $css; ?>">
                            <span class="fa fa-comment iconecube fa-5x"></span>
                        </div>
                        <div id="cube2<?php echo $css; ?>">
                            <div id="formulaire_commentaire">
                                <?php echo $contenusaisiecommentaire; ?>
                                <?php echo $commentaireposte; ?>
                            </div>
                        </div>
                        

                    </article>

                    <article class="cube">
                        <div id="cube3">
                            <?php echo $contenunbrecommentaire; ?>
                        </div>
                    </article>

                    <article class="cube">
                        <?php echo $flechegauche; ?>
                    </article>

                    <article class="cube">
                        <?php echo $cube5; ?>
                    </article>

                    <article class="cube">
                        <?php echo $flechedroite; ?>
                    </article>

                </section>   
            </div>

            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>
        </div>

    </body>
</html>


<?php require_once ('../controleur/controleforum.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/styleforum.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundforum">
                <section id="conteneur_forum">
                    <?php echo $contentalerteconnexionforum; ?>
                    
                    <div id="ancre"></div>
                    
                    <article class="cube">
                        <?php echo $cube1 ?>
                    </article>

                    <article class="cube">
                        <?php echo $cube2 ?>
                    </article>

                    <article class="cube">
                        <?php echo $cube3 ?>
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

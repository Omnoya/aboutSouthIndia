<?php require_once ('../controleur/controleactualite.php'); ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/styleactualite.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>

        <!-- Script Actualités -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="js/rssactualite.js"></script>
    </head>

    <body>
        <div id="bloc_page">
            <div id="backgroundactualite">
                <?php include_once ('inclusions/header.php'); ?>

                <div id="conteneur_actualite">
                    <div id="titre">
                        <h1>News</h1>
                    </div>
                </div>
            </div>

            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>

        </div>
    </body>
</html>


<?php require_once ('../controleur/controleinscription.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/styleinscription.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundinscription">
                <div id="ancre"></div>
                <div id="conteneur_inscription">
                    <div id="titre_inscription">
                        <h1>Inscription : </h1>
                    </div>
                    <?php echo $contenusaisieinscription; ?>
                </div>
            </div>
            
            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>
            
        </div>
    </body>
</html>


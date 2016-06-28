<?php require_once ('../controleur/controleparametresmembre.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/styleparametresmembre.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="background_param_membre">
                <div id="ancre"></div>
                <div id="conteneur_parametresmembre">
                    <div id="titre_param_membre">
                        <h1 id="ancre">Gestion de mes paramètres :</h1>
                    </div>
                    <?php echo $contenuparametresmembre; ?>
                </div>
            </div>
            
            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>
        </div>
    </body>
</html>
<?php require_once ('../controleur/controlemdpperdu.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/stylemdpperdu.css" />
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundmdpperdu">
                <div id="ancre"></div>
                <div id="conteneur_mdpperdu">
                    <div id="titre_mdpperdu">
                        <h1>Mot de passe oublié : </h1>
                    </div>
                    <?php echo $contenusaisiemdpperdu; ?>
                    <?php echo $messagereussiteenvoi; ?>
                </div>
            </div>
            
            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>
        </div>
    </body>
</html>
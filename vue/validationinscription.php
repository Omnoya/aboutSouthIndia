<?php require_once ('../controleur/controlevalidationinscription.php'); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/styleinscription.css" />
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundinscription">
                <div id="conteneur_inscription">
                    <div id="titre_inscription">
                        <h1>Validation de l'inscription : </h1>
                    </div>
                    <?php echo $contenuvalidationinscription; ?>
                </div>
            </div>
            
            <footer id="footer">

            </footer>
            
        </div>
    </body>
</html>

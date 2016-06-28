<?php require_once ('../controleur/controlearticle.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/stylearticle.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundarticle">



                <section id="conteneur_article">
                    <?php echo $contenuphoto ?>
                    <div id="contenu_nbre_article">
                        <div id="nbre_article">
                            
                            <div id="total_article"><p><?php echo "$nbre_total_article article(s) au total" ?></p></div>
                            <div id="compte_page"><p><?php echo "Page $page_num sur $nbre_total_article" ?></p></div>
                        </div>

                        <div id="bloc_pagination">
                            <div id="pagination"><?php echo $pagination ?></div>
                        </div>
                    </div>
                    
                    <div id="bloc_titre">
                        <div id="titre"><h1><?php echo $contenutitre ?></h1></div>
                    </div>

                    <div id="conteneur_lien">
                        <div class="titre_liste_article"><h1>Liste des articles :</h1></div>
                        <div class="conteneur_lien_article"><?php echo $lienarticle ?></div>
                    </div>

                    <article id="contenu_article">
                        <div id="article">
                            <div id="haut_page"><a id="lien_haut" href="#haut"><span class="fa fa-arrow-up fa-lg"></span></a></div>
                            <?php echo $contenuarticle ?>
                        </div>
                    </article>

                </section>

            </div>

            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>
        </div>
    </body>
</html>
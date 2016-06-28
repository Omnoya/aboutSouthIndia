<?php require_once ('../../controleur/controleur_admin/c_gestionmessageforum.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../css/styleadmin.css" />
        <title>Inde du sud</title>
    </head>
    <body>
        <div id="bloc_page">
            <?php include_once ('headeradmin.php'); ?>
            <div id="conteneur_indexadmin">
                <div id="conteneur_gestion_messageforum">
                    <section>
                        <?php echo $contenuindicationsuppressionmessageforum ?>
                        <?php echo $contenugestionmessageforum ?>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>


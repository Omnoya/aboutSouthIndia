<?php require_once ('../../controleur/controleur_admin/c_gestionalbumphoto.php'); ?>
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
            <div id="conteneur_indexadminphoto">
                <div id="conteneur_gestion_albumphotos">
                    <section>
                        <?php echo $contenuformulairegestionphoto ?>
                        <?php echo $contenumessagesuppressionphoto ?>
                        <?php echo $contenutableaugestionphoto ?>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>


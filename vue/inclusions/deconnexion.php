<?php
require_once ('../../modele/fonction.php');

session_destroy();

header('Location:' . $_SERVER["HTTP_REFERER"]);




<?php
//Config to change by the user
$titre="Actions!FAAA";
$dbserver="localhost";
$db="actionfaaa";
$dbuser="actions";
$dbpwd="actions";
$dbport="3306"; //port 3306 par défaut
$apppath=substr(strrchr($_SERVER['HTTP_REFERER'], "\/"), 1);
$version="version 1c";

// declare Constants
define("TITRE", $titre);
define("DBSERVER", $dbserver);
define("DB", $db);
define("DBUSER", $dbuser);
define("DBPWD", $dbpwd);
define("DBPORT", $dbport);
define("APPPATH", $apppath);
define("VERSION", $version);

?>

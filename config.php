<?php
//Config to change by the user
$titre="Actions!FAAA";
$dbserver="localhost";
$db="actionfaaa";
$dbuser="root";
$dbpwd="topaze";
$dbport="3306"; //port 3306 par défaut
$apppath=substr(strrchr($_SERVER['HTTP_REFERER'], "\/"), 1);
$version="version 1b (build ".getbuild().")";

// declare Constants
define("TITRE", $titre);
define("DBSERVER", $dbserver);
define("DB", $db);
define("DBUSER", $dbuser);
define("DBPWD", $dbpwd);
define("DBPORT", $dbport);
define("APPPATH", $apppath);
define("VERSION", $version);

function getbuild(){
	$file = ".bzr/branch/last-revision";
	if (file_exists($file)) {
		$fh = fopen($file, 'r');
		$builddata = fread($fh, filesize($file));
		fclose($fh);
		$build = preg_split("/\ /",$builddata);
		return $build[0];
	}
}

?>

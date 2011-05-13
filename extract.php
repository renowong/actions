<?php
include_once('includes/top.php');
include_once('menu.php');

/* function */



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Action!FAAA</title> 
                <link rel="SHORTCUT ICON" href="images/favicon.ico">
		<style media="all" type="text/css">@import "css/default.css";</style>
		<style media="all" type="text/css">@import "css/extract.css";</style>
		<style media="all" type="text/css">@import "css/epoch_styles.css";</style>
		<script src="includes/epoch_classes.js" type="text/javascript"></script>
		<script src="includes/jquery-1.3.1.min.js" type="text/javascript"></script>
		<script src="includes/extract.js" type="text/javascript"></script>
		<script type="text/javascript" src="includes/zxml.js"></script>
		<script type="text/javascript">
			var oXmlHttp = zXmlHttp.createRequest();
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				
			});
		</script>
		<script type="text/javascript">
			var dp_cal;      
			window.onload = function () {
				dmd_dp_cal  = new Epoch('epoch_popup_dmd_dp_cal','popup',document.getElementById('popup_container_dmd_dp_cal'));
				dmd_fin_cal  = new Epoch('epoch_popup_dmd_fin_cal','popup',document.getElementById('popup_container_dmd_fin_cal'));
				ech_dp_cal  = new Epoch('epoch_popup_ech_dp_cal','popup',document.getElementById('popup_container_ech_dp_cal'));
				ech_fin_cal  = new Epoch('epoch_popup_ech_fin_cal','popup',document.getElementById('popup_container_ech_fin_cal'));
			};
		</script>

	</head>
	<body>
		<?php echo $logoninfo ?>
		<?php echo $menu ?>
		<div id="criteria">
			<form id="frmextract" name="frmextract" action="includes/extractsearch.php" method="post">
			<table id="criteriatbl">
				<tr>
					<td>
						Dossier
						<select id="byfolder" name="byfolder">
							<option value="%">Tous les Dossiers</option>
							<? echo buildprojectslist($_SESSION['userid']); ?>
						</select>
						Responsable
						<select id="byincharge" name="byincharge">
							<option value="%">Tous les charg&eacute;s</option>
							<? echo buildinchargelist(); ?>
						</select>
						Archive
						<select id="byarchive" name="byarchive">
							<option value="1">Actifs</option>
							<option value="0">Archiv&eacute;</option>
						</select>
						<hr/>
						Date dmd. entre
						<input id="popup_container_dmd_dp_cal" name="popup_container_dmd_dp_cal" type="text" size="8" /> <input type="button" value="Choisir" onclick="dmd_dp_cal.toggle();"/> et <input id="popup_container_dmd_fin_cal" name="popup_container_dmd_fin_cal" type="text" size="8" /> <input type="button" value="Choisir" onclick="dmd_fin_cal.toggle();"/>
						Date &eacute;ch&eacute;ance entre
						<input id="popup_container_ech_dp_cal" name="popup_container_ech_dp_cal" type="text" size="8" /> <input type="button" value="Choisir" onclick="ech_dp_cal.toggle();"/> et <input id="popup_container_ech_fin_cal" name="popup_container_ech_fin_cal" type="text" size="8" /> <input type="button" value="Choisir" onclick="ech_fin_cal.toggle();"/>
						<hr/>	
						<input type="button" id="searchbtn" onClick="extracttrigger(this)" value="Rechercher" />
					</td>
				</tr>
			</table>
			</form>
		</div>
		<div id="extractresults" /> 
	</body>
</html>

<?
function buildprojectslist($uid) {
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();}

        $query = "SELECT * FROM `projects` WHERE `active` = 1 AND (`ownerid` = '$uid') ORDER BY `projects`.`title`";
        #echo $query;

        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $output .= "<option value='".$row["projectid"]."'>".$row["title"]."</option>";
                }
                $result->close();
        }

        $mysqli->close();
        return $output;
}

function buildinchargelist() {
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();}

        $query = "SELECT * FROM `users` WHERE `users`.`active` = '1' ORDER BY `last`";

        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $output .= "<option value='".$row["userid"]."'$select>".$row["last"]." ".$row["first"]."</option>";
                }
                $result->close();
        }

        $mysqli->close();
        return $output;
}
?>

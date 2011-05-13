<?php

include_once('includes/class_user.php');
if (($_GET['edit'])>0){
        $aracc =  getaccountinfo($_GET['edit']);
        $cAcc = new User;
        $cAcc->userid = $aracc[0];
        $cAcc->login = $aracc[1];
        $cAcc->password = "******";
        $cAcc->last = $aracc[3];
        $cAcc->first = $aracc[4];
        $cAcc->email = $aracc[5];
        $cAcc->level = $aracc[6];
        $cAcc->active = $aracc[7];
}

(($_GET['edit'])==$Cuser->userid ? $ownacc=true : $ownacc=false);

$addaccount = "

<div id=\"newaccountdiv\">
	<form method=\"post\" action=\"addaccount.php\" name=\"frmadd\" id=\"frmadd\">
	<table id=\"newaccounttable\">
		<tr>
			<th>Nom d'utilisateur</th>
			<td>
				<input name=\"login\" id=\"login\" class=\"lowercase validate\" type=\"text\" size=\"25\" maxlength=\"25\" value=\"$cAcc->login\"/>
			</td>
		</tr>
		<tr>
			<th>Mot de passe</th>
			<td>
				<input name=\"password\" id=\"password\" class=\"validate\" type=\"text\" size=\"25\" maxlength=\"10\" value=\"$cAcc->password\"/>
			</td>
		</tr>
		<tr>
			<th>Nom</th>
			<td><input name=\"lastname\" id=\"lastname\" class=\"firstcase validate\" type=\"text\" size=\"25\" maxlenght=\"15\" value=\"$cAcc->last\"/></td>
		</tr>
		<tr>
			<th>Pr&eacute;nom</th>
			<td><input name=\"firstname\" id=\"firstname\" class=\"firstcase validate\" type=\"text\" size=\"25\" maxlength=\"15\" value=\"$cAcc->first\"/></td>
		</tr>
		<tr>
			<th>Email</th>
			<td><input name=\"email\" id=\"email\" type=\"text\" size=\"50\" class=\"lowercase validate\" maxlength=\"50\" value=\"$cAcc->email\"/></td>
		</tr>
		<tr>
			<th>Niveau</th>
			<td>
				<select name=\"level\">";
$addaccount .= buildlevellist($cAcc->level,$Cuser->level,$ownacc); 
$addaccount .= "			</select>
				</select>
			</td>
		</tr>
		<tr>
			<th>Actif/Inactif</th>
			<td>
				<select name=\"active\">";
$addaccount .= buildstatuslist($cAcc->active,$ownacc); 
$addaccount .= "			</select>
				</select>
			</td>
		</tr>
		<tr>
			<input type=\"hidden\" name=\"userid\" value=\"".(isset($cAcc->userid) ? $cAcc->userid : 0)."\"/>
			<td colspan=\"2\"><input type=\"button\" id=\"newaccountcancel\" value=\"Annuler\"/> <input type=\"submit\" id=\"submit\" class=\"hidden\" value=\"Enregistrer\"/></td>
		</tr>
	</table>
	</form>
</div>
";

if($Cuser->level=='3'){$addaccount="";}

/* functions */

function buildlevellist($select,$level,$ownacc) {
	if($level!=='1'){
		if($ownacc && $level==2){$output .= "<option value=\"2\" selected>Directeur</option>";}
		$output .= "<option value=\"3\">Agent</option>";
	}else{
		$arr = array(1=>"Administrateur", 2=>"Directeur", 3=>"Agent");
		for ($count=3;$count>=1;$count-=1) {
			($select==$count ? $selected=" selected" : $selected="");
			$output .= "<option value=\"$count\"$selected>$arr[$count]</option>";
		}
	}

        return $output;
}

function buildstatuslist($select,$ownacc) {
	if($ownacc){
		$output .= "<option value=\"1\">Actif</option>";
	}else{
		$arr = array(0=>"Inactif", 1=>"Actif");
			for ($count=1;$count>=0;$count-=1) {
			($select==$count ? $selected=" selected" : $selected="");
			$output .= "<option value=\"$count\"$selected>$arr[$count]</option>";
		}
	}

	return $output;
}

function getaccountinfo($u){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `users`.`userid`, `users`.`login`, `users`.`password`, `users`.`last`, `users`.`first`, `users`.`email`, `users`.`level`, `users`.`active` FROM `users` WHERE `userid` = '" . $u . "'";
        if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                $result->close();
        }
        $mysqli->close();
        return $row;
}

?>

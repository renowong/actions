<?php
$addproject = "
<div id=\"newprojectdiv\">
	<form method=\"post\" action=\"addproject.php\" name=\"frmadd\" id=\"frmadd\">
	<input type=\"hidden\" name=\"projectid\">
	<table id=\"newprojecttable\">
		<tr>
			<th>Titre </th>
			<td><input name=\"title\" id=\"title\" type=\"text\" size=\"40\" maxlength=\"50\"></td>
		</tr>
		<tr>
			<th>Description </th>
			<td><textarea cols=\"40\" rows=\"4\" name=\"description\">Description ici</textarea>
			 </td>
		</tr>
		<tr>
			<input type=\"hidden\" name=\"userid\" value=\"$Cuser->userid\"/>
			<td colspan=\"2\"> <input type=\"button\" id=\"newprojectcancel\" value=\"Annuler\"/> <input type=\"submit\" id=\"submit\" class=\"hidden\" value=\"Enregistrer\"/></td>
		</tr>
	</table>
	</form>
</div>
";

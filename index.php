<?php
include_once('config.php');

$login = $_POST['login'];
$password = MD5($_POST['password']);
$failed = $_GET['failed'];
(isset($_GET['expired']) ? $divmessage = "<p class=\"expired\">Session Expir&eacute;e<br/>Veuillez vous relogger</p>" : $divmessage = "");
if (strlen($login)>0) {
	if(checklogin($login,$password)) {
		header('Location:main.php?sessionid='.create_session($login,$password));
	}
	else {
		header('Location:'.$_SERVER['PHP_SELF'].'?failed=0');
	};
}


/* functions */
function create_session($l,$p){
$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	
	$query = "SELECT * FROM `users` " .
		"WHERE `login` = '" . $l . "' " .
		"AND `password` = '" . $p . "'";

	if ($result = $mysqli->query($query)) {
		$row = $result->fetch_row();
		$result->close();
	} 

	$userid = $row[0];
	$sessionid = MD5(date('l dS \of F Y h:i:s A'));
	$datetime = date('Y-m-d H:i:s');
	$level = $row[4];
	$query = "INSERT INTO `actionfaaa`.`sessions` (`id` , `sessionid` , `datetime` , `userid` , `level` , `active`)" .
	"VALUES ( NULL , '".$sessionid."', '".$datetime."', '".$userid."', '".$level."', '1')";
	$mysqli->query($query);
	$query = "UPDATE `actionfaaa`.`users` SET `lastsession` = '".$datetime."' WHERE `userid` = ".$userid;
	$mysqli->query($query);
	$mysqli->close();
	
	return $sessionid;
}

function checklogin($l,$p){
$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	
	$query = "SELECT * FROM `users` " .
		"WHERE `login` = '" . $l . "' " .
		"AND `password` = '" . $p . "' AND `active`='1'";

	if ($result = $mysqli->query($query)) {
		$row_cnt = $result->num_rows;
		$result->close();
	} 

	$mysqli->close();
	return $row_cnt;
}


?>


<html>
	<head>
		<title>Action!FAAA</title>
		<link rel="SHORTCUT ICON" href="images/favicon.ico">
		<style media="all" type="text/css">@import "css/default.css";</style>
		<script type="text/javascript">
			function login(){
				var oform = document.getElementById("formlogin");
				var slogin = oform.elements["login"].value;
//				alert(slogin);
				var spassword = oform.elements["password"].value;
//				alert(spassword);
				oform.submit();
//				alert('document submitted');
				}
			function reset(){
				var oform = document.getElementById("formlogin");
				var slogin = oform.elements["login"];
				var spassword = oform.elements["password"];
				slogin.value = "";
				spassword.value = "";
			}
		</script>
		<script type="text/javascript" src="includes/jquery-1.3.1.min.js"></script>
		<script type="text/javascript">
                        $(document).ready(function(){
                                $('#password').keypress(function(e) {
					if (e.which == 13) {
						$('#submit').click();
					}
                                });
                                $('#login').keypress(function(e) {
					if (e.which == 13) {
						$('#submit').click();
					}
                                });
				$('#login').focus();
                        });
                </script>

	</head>
	<body>
		<div id="sessionexp"><? echo $divmessage ?></div>
		<div id="divlogin">
		<form class="formlogin" name="formlogin" id="formlogin" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
			<fieldset>
				<legend>Login</legend>
					<p><img src="images/actions.png" /></p>
					<p><label>Identifiant:</label> <input id="login" name="login" type="text" maxlength="10" /></p>
					<p>Mot de passe:</label> <input id="password" name="password" type="password" maxlength="10"></p>
					<div class="version"><?php echo $version ?></div>
			</fieldset>
		</form>
			<input type="button" name="reset" id="reset" value="Annuler" onclick="reset();" /> <input type="button" name="submit" id="submit" value="Entrer" onclick="login();" />
		</div>
	</body>
</html>

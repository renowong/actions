<?php
include_once('includes/top.php');
include_once('menu.php');
include_once('includes/accountslist.php');
include_once('includes/accountfunctions.php');
include_once('newaccount.php');

/* function */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Action!FAAA</title> 
                <link rel="SHORTCUT ICON" href="images/favicon.ico">	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style media="all" type="text/css">@import "css/default.css";</style>
		<script type="text/javascript" src="includes/jquery-1.3.1.min.js"></script>
		<script type="text/javascript" src="includes/jquery.getUrlParam.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#newaccountdiv').hide();

				$('#newaccountcancel').click(function() {
					$('#newaccountdiv').hide();
					$('#fuzzydiv').addClass('hidden');
				});

				if ($(document).getUrlParam("edit")!==null) {
					$('#fuzzydiv').removeClass('hidden');
                                        $('#newaccountdiv').fadeIn(1000);
                                };

				$('.b_dele').click(function(e){
                                        e.stopPropagation();
                                        var id = ($(this).attr("id"));
                                        var yesno = confirm("Etes-vous sûr(e) de vouloir supprimer cet utilisateur?");
                                        if(yesno){
                                                window.location.href='users.php?delete='+id;
                                        };
                                });

				$('#email').focus(function() {
					var login = $('#login').val();
					if($(this).val()==''){$(this).val(login+'@mairiefaaa.pf');}
				});
				
				$('#email').blur(function() {
					if (!validateemail($(this).val())){
						alert("Email invalide");
					}
				});

				$('.lowercase').blur(function() {
					$(this).val($(this).val().toLowerCase());
				});

				$('.firstcase').blur(function() {
					var str=$(this).val();
					var fstr=str.substring(0,1);
					fstr=fstr.toUpperCase();
					str=str.substring(1);
					str=str.toLowerCase();
					$(this).val(fstr+str);
				});

				$('.validate').blur(function() {
					var valid=true;
					if($('#login').val()==""){valid=false;}	
					if($('#password').val()==""){valid=false;}	
					if($('#last').val()==""){valid=false;}	
					if($('#first').val()==""){valid=false;}	
					if($('#email').val()=="" || !validateemail($('#email').val())){valid=false;}	
					if(valid){$('#submit').fadeIn(1000);}else{$('#submit').fadeOut(1000);}
				});
				
				function validateemail(email) {
					var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
					if (!pattern.test(email)){
						return false;
					}else{
						return true;
					}
				}
			});
		</script>

	</head>
	<body>
		<?php echo $logoninfo ?>
		<?php echo $menu ?>
		<div id="fuzzydiv" class="hidden"></div>
		<table id="accounts">
			<tr>
				<th>Utilisateur</th>
				<th>Nom</th>
				<th>Pr&eacute;nom</th>
				<th>Email</th>
				<th>Niveau</th>
				<th>Actif/Inactif</th>
				<th>Derni&egrave;re Session</th>
				<th style="width:60px;"><a href="users.php?edit=0" id="newaccountshow"><img src='images/b_new.png'></a></th>
			</tr>
			<?php echo getaccounts($Cuser->level,$Cuser->userid); ?>
		</table>
		<?php echo $addaccount ?>
	</body>
</html>

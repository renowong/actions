<?php
include_once('includes/top.php');
include_once('menu.php');
include_once('includes/projectfunctions.php');
include_once('includes/projectlist.php');
include_once('newproject.php');

/* function */
function order($col){
	$cur = strval($_SERVER['PHP_SELF']);
	(isset ($_GET['project']) ? $get="&project=".$_GET['project'] : $get="");
	if (!isset ($_GET['dir']) || $_GET['dir']=="ASC") {$nextdir="DESC";} else {$nextdir="ASC";}
	(substr($cur,-3)=="php" ? $car="?" : $car="&");
	return $cur.$car."order=".$col."&dir=".$nextdir.$get;
}

$projecttableth = "<th style=\"width:200px;\"><a href=\"".order(title)."\">Dossier</a></th>
<th><a href=\"".order(description)."\">Description</a></th>
<th style=\"width:100px;\"><a href=\"".order(active)."\">Status</a></th>
<th style=\"width:100px;\">T&acirc;ches</th>
<th style=\"width:60px;\"><a href=\"javascript:void(0)\" id=\"newprojectshow\"><img src='images/b_new.png'></a></th>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Action!FAAA</title> 
                <link rel="SHORTCUT ICON" href="images/favicon.ico">
		<style media="all" type="text/css">@import "css/default.css";</style>
		<script src="includes/jquery-1.3.1.min.js" type="text/javascript"></script>
		<script src="includes/datetimepicker.js" type="text/javascript"></script>
		<script src="includes/confirmation.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#newprojectdiv').hide();
				
				$('#newprojectcancel').click(function() {
					$('#newprojectdiv').hide();
					$('#fuzzydiv').addClass('hidden');
				});

				$('#newprojectshow').click(function() {
					$('#newprojectdiv :input[name=title]').val('');
					$('#newprojectdiv :input[name=description]').val('');
					$('#newprojectdiv :input[name=projectid]').val(0);
					$('#newprojectdiv').fadeIn(1000);
					$('#fuzzydiv').removeClass('hidden');
				});

				$('.edittrigger').click(function() {
					var $id=$(this).attr('projectid');
					var $title=$('[name=hid_title_'+$id+']').val();
					var $description=$('[name=hid_description_'+$id+']').val();
					$('#newprojectdiv :input[name=title]').val($title);
					$('#newprojectdiv :input[name=description]').val($description);
					$('#newprojectdiv :input[name=projectid]').val($id);
					$('#newprojectdiv').fadeIn(1000);
					$('#submit').show();
				});
				
				$('#title').blur(function() {
					if($(this).val()==""){$('#submit').fadeOut(1000);}else{$('#submit').fadeIn(1000);}
				});	
				
			});
		</script>
	</head>
	<body>
		<?php echo $logoninfo ?>
		<?php echo $menu ?>
		<div id="fuzzydiv" class="hidden"></div>
		<table id="listtaches">
			<tr>
			<?php echo $projecttableth ?>
			</tr>
			<?php echo $projectlist ?>
		</table>
		<?php echo $addproject ?>
	</body>
</html>

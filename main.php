<?php
include_once('includes/top.php');
include_once('menu.php');
include_once('includes/taskfunctions.php');
//include_once('includes/comfunctions.php');
include_once('includes/tasklist.php');
include_once('newtask.php');

/* function */
function order($col){
	$cur = strval($_SERVER['PHP_SELF']);
	(isset ($_GET['project']) ? $get="&project=".$_GET['project'] : $get="");
	if (!isset ($_GET['dir']) || $_GET['dir']=="ASC") {$nextdir="DESC";} else {$nextdir="ASC";}
	(substr($cur,-3)=="php" ? $car="?" : $car="&");
	(isset ($_GET['archive']) ? $archive="&archive=1" : $archive="");
	return $cur.$car."order=".$col."&dir=".$nextdir.$get.$archive;
}

$tasktableth = "<th style=\"width:200px;\"><a href=\"".order(projecttitle)."\">Dossier</a></th>
<th><a href=\"".order(title)."\">Situation</a></th>
<th style=\"width:150px;\"><a href=\"".order(userid)."\">Propri&eacute;taire</a></th>
<th style=\"width:150px;\"><a href=\"".order(incharge)."\">Responsable</a></th>
<th style=\"width:60px;\"><a href=\"".order(date)."\">Date dmd</a></th>
<th style=\"width:60px;\"><a href=\"".order(deadline)."\">&Eacute;ch&eacute;ance</a></th>
<th style=\"width:40px;\">Com.</th>
<th style=\"width:160px;\"><a href=\"".order(progress)."\">&Eacute;tat</a></th>
<th style=\"width:60px;\"><a href=\"main.php?edit=0\"><img src='images/b_new.png'></a></th>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Action!FAAA</title>
		<link rel="SHORTCUT ICON" href="images/favicon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style media="all" type="text/css">@import "css/default.css";</style>
		<style media="all" type="text/css">@import "css/tabs.css";</style>
		<script type="text/javascript" src="includes/jquery-1.3.1.min.js"></script>
		<script type="text/javascript" src="includes/jquery.getUrlParam.js"></script>
		<script type="text/javascript" src="includes/jquery.dimensions.js">/*to be used with tooltips*/</script>
		<script type="text/javascript" src="includes/jquery.tooltip.js"></script>
		<script type="text/javascript" src="includes/datetimepicker.js"></script>
		<script type="text/javascript" src="includes/tabber.js"></script>
                <script type="text/javascript" src="includes/zxml.js"></script>
                <script type="text/javascript">
			var oXmlHttp = zXmlHttp.createRequest();
		</script> <!--doit etre avant les copy.js et followup.js //-->

		<script type="text/javascript" src="includes/copy.js"></script>
		<script type="text/javascript" src="includes/followup.js"></script>
		<script type="text/javascript" src="includes/comments.js"></script>
		
		<!--Autosuggest //-->
		<script type="text/javascript" src="js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
		<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
	
		<script type="text/javascript">
			$(document).ready(function(){
				$('#newtaskdiv').hide();

				if ($(document).getUrlParam("edit")!==null) {
					$('#newtaskdiv').fadeIn(1000);
					if ($(document).getUrlParam("edit")!=='0' && $(document).getUrlParam("archive")!=='1'){
						$('#enregistrer').show();
						if($('#owner').val()=='1' && $('#title').val()!=='' && $('#enddate').val()!==''){$('#archiver').show()};
					}
					if ($(document).getUrlParam("archive")=='1'){
						$('#addcomment').hide();
						$('.cancel').val('Fermer');
					}

					if ($(document).getUrlParam("edit")>0) {
						//window.frames['ifrcomments'].document.body.innerHTML = "<?php echo $cTask->comments ?>";
						//copytrigger(document.getElementById('selectnames')); //preselect the forced copies
						$('#ifrcommentsdiv').append("<?php echo $cTask->comments ?>");
					}

					if ($('#newtaskdiv').length>0) {$('#fuzzydiv').removeClass('hidden');}
		
					if ($('#enddate').val()!=='' && $(document).getUrlParam("archive")!=='1' && $(this).val()=='100'){$('#archiver').show();};
					if ($('#progress').val()=='100') {$('#imgenddate').show();}else{$('#enddatelock').show();}
					
					if ($('#followups').val()>'0') {populate_followup();}else{$('select#followupincharge').val($('#incharge').val());}

					if ($(document).getUrlParam("followup")!==null) {$('select#followups').val($(document).getUrlParam("followup"));populate_followup();if($('#owner').val()!=='1'){$('#deletefollowup').hide();}}

				};
				

				$('tr').tooltip();

				$('.b_dele').click(function(e){
					e.stopPropagation();
					var id = ($(this).attr("id"));
					var yesno = confirm("Etes-vous sûr(e) de vouloir supprimer cette tâche?");
					if(yesno){
						window.location.href='main.php?delete='+id;
					};
				});

				$('#addcomment').keyup(function(){
					if($(this).val()!==''){$('#ajouter').fadeIn(1000)}else{$('#ajouter').fadeOut(1000)};
				});

				$('#addcomment').click(function(){
					if($(this).val()=='Cliquez ici pour ajouter un nouveau commentaire'){$(this).val('');};
				});

				$('#title').keyup(function(){
					if($(this).val()!==''){
						$('#enregistrer').fadeIn(1000);
						if($('#owner').val()=='1' && $('#title').val()!=='' && $('#enddate').val()!==''){$('#archiver').fadeIn(1000)};
					} else {
						$('#enregistrer').fadeOut(1000);
						$('#archiver').fadeOut(1000);
					}
				});
				
				$('#followuptitle').keyup(function(){
                                        if($(this).val()!==''){
                                                $('#enregistrerfollowup').fadeIn(1000);
                                        } else {
                                                $('#enregistrerfollowup').fadeOut(1000);
                                        }
                                });


				$('#progress').change(function(){
					if($(this).val()=='100'){
						$('#imgenddate').fadeIn(1000);
						$('#enddatelock').fadeOut(1000);
					}else{
						$('#imgenddate').fadeOut(1000);
						$('#enddatelock').fadeIn(1000);
						$('#enddate').val('');
						$('#archiver').fadeOut(1000);
					};
				});

				$('#imgenddate').click(function(){
					if($('#owner').val()=='1' && $('#title').val()!=='' && $(document).getUrlParam("archive")!=='1'){$('#archiver').fadeIn(1000)};
				});

				$('#followups').change(function(){
					if($('#followups').val()>0){
					populate_followup($('#owner').val());
					}else{
					$('#followuptitle').val('');
					$('select#followupincharge').val($('#incharge').val());
					$('#deadlinef').val('');
					$('#enddatef').val('');
					$('#enregistrerfollowup').fadeOut(1000);
					$('#deletefollowup').fadeOut(1000);
					$('#imgenddatef').fadeOut();
					}
				});	
				
	                        $('.fdelete').click(function(e){
                                        var id = ($('#followuptodelete').val());
                                        var yesno = confirm("Etes-vous sûr(e) de vouloir supprimer ce suivi?");
                                        if(yesno){
                                                window.location.href='main.php?deletef='+id;
                                        };
                                });


				var forcedcopies = $('#forcedcopies').val(); //add forced copies to form copies when loading
				forcedcopies = forcedcopies.split("|");
				for (var i = 0; i < forcedcopies.length; i++) {$('#selectnames option[value="'+forcedcopies[i]+'"]').attr("selected", "selected");}
			});

//------------------------functions------------------------------------------------

	function populate_followup(owner){
		var fid = ($('#followups').attr("selectedIndex"));
		fid--; //fix to ignore the first option
		$('#followuptitle').val(($('#followuptitle'+fid).val()));
		$('select#followupincharge').val($('#followupincharge'+fid).val());
		$('#deadlinef').val(($('#followupdeadline'+fid).val()));
		$('#enddatef').val(($('#followupenddate'+fid).val()));  
		if ($('#followuptitle').val()!=='') {$('#enregistrerfollowup').fadeIn(1000);}
		if(owner=='1'){$('#deletefollowup').fadeIn(1000);}
		$('#followuptodelete').val($('#followups').val());
		if($('#currentuserid').val()==$('#followupincharge').val()) {$('#imgenddatef').fadeIn(1000);}else{$('#imgenddatef').fadeOut(1000);}
	}
		</script>

	</head>
	<body>
		<?php echo $logoninfo ?>
		<?php echo $menu ?>
		<div id="fuzzydiv" class="hidden"></div>
		<table id="listtaches">
			<tr>
			<?php echo $tasktableth ?>
			</tr>
			<?php echo $tasklist ?>
		</table>
		<?php echo $addtask ?>
		
	<!--autosuggest //-->
	<script type="text/javascript">
		var options_xml = {
			script: function (input) { return "test.php?input="+input+"&testid="+document.getElementById('testid').value; },
			varname:"input"
		};
		var as_xml = new bsn.AutoSuggest('title', options_xml);
	</script>
	</body>
</html>

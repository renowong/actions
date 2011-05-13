function deletecom(id){
	if (oXmlHttp) {
                   if ((oXmlHttp.readyState == 4 ||oXmlHttp.readyState == 0)) {
			oXmlHttp.open("get", "includes/comfunctions.php?deletecom="+id, true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        //oXmlHttp.onreadystatechange = handleRequestStateChangeFollowup;
			oXmlHttp.send(null);
			//alert("comment deleted");
			$('#com'+id).addClass('hidden');
			}			
	}
}


function commentstrigger(ob){

	post_formComments(document.getElementById("frmaddcom"));
}

function post_formComments(f){
	//var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBodyComments(f);
	//alert(sBody);
	if (oXmlHttp) {
		try {
		    if ((oXmlHttp.readyState == 4 ||oXmlHttp.readyState == 0)) {
			oXmlHttp.open("post", f.action, true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			oXmlHttp.onreadystatechange = handleRequestStateChangeComment;
			oXmlHttp.send(sBody);
		    }
		}
		catch (e) {
		    alert(e.toString());
		}
	}	
}

function handleRequestStateChangeComment(){
	if (oXmlHttp.readyState == 4) {
		if (oXmlHttp.status == 200) {
			var comment = readResponseComment();
			//alert(lastid);
			addnewcom(comment[0],comment[1],comment[2],comment[3],comment[4]); //update list
			//alert("Commentaires mis à jour");
		}
		else {
			alert("Une erreur est survenue: " + oXmlHttp.statusText);
		}
	}
}

function readResponseComment(){
	var response = oXmlHttp.responseText;
	var result = new Array();
	//alert("response = " + response);
	responseXml = oXmlHttp.responseXML;
	xmlDoc = responseXml.documentElement;
	result[0] = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
	result[1] = xmlDoc.getElementsByTagName("comment")[0].firstChild.data;
	result[2] = xmlDoc.getElementsByTagName("date")[0].firstChild.data;
	result[3] = xmlDoc.getElementsByTagName("last")[0].firstChild.data;
	result[4] = xmlDoc.getElementsByTagName("first")[0].firstChild.data;
	return result;
}

function getRequestBodyComments(oForm) {
	var aParams = new Array();
	for (var i=0 ; i < oForm.elements.length; i++) {
	var sParam = encodeURIComponent(oForm.elements[i].name);
	sParam += "=";
	if(oForm.elements[i].checked){
	sParam += "1";
	}
	else {
	sParam += encodeURIComponent(oForm.elements[i].value);
	}
	aParams.push(sParam);
	}
	//alert (aParams);
	return aParams.join("&");
}

function addnewcom(lastid,comment,datecom,last,first) {
	var comfield = $('#ifrcommentsdiv');

	comfield.prepend("<div id='com"+lastid+"'><p><span style='font-size:10px;font-style:italic;'>"+datecom+" "+last+" "+first+"</span> <a href='javascript:deletecom("+lastid+")'><img border='0' src='images/b_delx.png'/></a><br/><span style='font-size:12px'><< "+comment+" >></span></p><hr/></div>");

	$('#addcomment').val(''); //reset the form
	$('#ajouter').fadeOut(1000);
}


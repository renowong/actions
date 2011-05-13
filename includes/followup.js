function followuptrigger(ob){

	post_formFollowup(document.getElementById("frmaddfollowup"));

}

function post_formFollowup(f){
	//var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBodyFollowup(f);
	//alert(sBody);
	if (oXmlHttp) {
		try {
		    if ((oXmlHttp.readyState == 4 ||oXmlHttp.readyState == 0)) {
			oXmlHttp.open("post", f.action, true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			oXmlHttp.onreadystatechange = handleRequestStateChangeFollowup;
			oXmlHttp.send(sBody);
		    }
		}
		catch (e) {
		    alert(e.toString());
		}
	}	
}

function handleRequestStateChangeFollowup(){
	if (oXmlHttp.readyState == 4) {
		if (oXmlHttp.status == 200) {
			var lastid = readResponseFollowup();
			//alert("ok");
			addhiddeninput(lastid); //update list
			alert("Suite à donner, mise à jour");
		}
		else {
			alert("Une erreur est survenue: " + oXmlHttp.statusText);
		}
	}
}

function readResponseFollowup(){
	var response = oXmlHttp.responseText;
	//alert("response = " + response);
	responseXml = oXmlHttp.responseXML;
	xmlDoc = responseXml.documentElement;
	result = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
	return result;
}

function getRequestBodyFollowup(oForm) {
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

function addhiddeninput(lastid) {
	var count = document.getElementById("followupcount").value;
	var title = document.getElementById("followuptitle").value;
	var incharge = document.getElementById("followupincharge").value;
	var deadline = document.getElementById("deadlinef").value;
	var enddate = document.getElementById("enddatef").value;
	var currentid = document.getElementById("followups").value;
	var currentidx = document.getElementById("followups").selectedIndex;
	var forcedcopies = $('#forcedcopies').val();

	$('#selectnames option[value="'+incharge+'"]').attr("selected", "selected"); // select the incharge copie
	$('#forcedcopies').val(forcedcopies+"|"+incharge+"|");
	$('#forcedcopies').val($('#forcedcopies').val().replace('||','|')); //fix double pipe
	//alert($('#forcedcopies').val());

	currentidx--; 
	if (currentidx==-1){ //new entry
		$('#hiddendata').append('<input type="hidden" name="followuptitle'+count+'" id="followuptitle'+count+'" value="'+title+'">');
		$('#hiddendata').append('<input type="hidden" name="followupincharge'+count+'" id="followupincharge'+count+'" value="'+incharge+'">');
		$('#hiddendata').append('<input type="hidden" name="followupdeadline'+count+'" id="followupdeadline'+count+'" value="'+deadline+'">');
		$('#hiddendata').append('<input type="hidden" name="followupenddate'+count+'" id="followupenddate'+count+'" value="'+enddate+'">');
		count++;
		$('#followupcount').val(count);
		$('#followups').append('<option value="'+lastid+'">'+title+'</option>');
		$('#followups')[0].selectedIndex=document.getElementById("followups").options.length-1;
	}else{ // update existing entry to list
		document.getElementById("followuptitle"+currentidx).value = title;
		document.getElementById("followupincharge"+currentidx).value = incharge;
		document.getElementById("followupdeadline"+currentidx).value = deadline;
		document.getElementById("followupenddate"+currentidx).value = enddate;	
		document.getElementById("followups").options[currentidx+1].text = title;
	}
}


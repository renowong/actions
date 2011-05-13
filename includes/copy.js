function copytrigger(ob){

	selected = new Array();
	var copiesID="|";
	var forcedcopies = $('#forcedcopies').val();
	forcedcopies = forcedcopies.split("|");

	//check to see if "aucune copie" is selected
	if (ob.selectedIndex==0) {
		copiesID="";
		ob.selectedIndex=0;
	}else{ 
		for (var i = 0; i < ob.options.length; i++) {if (ob.options[i].selected) selected.push(ob.options[i].value);}
		for (var i = 0; i < selected.length; i++) {copiesID += selected[i] + "|";}
	}
	

	for (var i = 0; i < forcedcopies.length; i++) {$('#selectnames option[value="'+forcedcopies[i]+'"]').attr("selected", "selected");}

	// send back concatened data to form id copylist then create zxml to submit
	var formlist = document.getElementById("copylist");
	formlist.value = copiesID;

	//remove forced copies to remove duplicates when listing
	for (var i = 0; i < forcedcopies.length; i++) {copiesID = copiesID.replace(forcedcopies[i] + "|","");}	

	post_formCopy(document.getElementById("frmaddcopies"));

}

function post_formCopy(f){
	//var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBodyCopy(f);
	if (oXmlHttp) {
		try {
		    if ((oXmlHttp.readyState == 4 ||oXmlHttp.readyState == 0)) {
			oXmlHttp.open("post", f.action, true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			oXmlHttp.onreadystatechange = handleRequestStateChangeCopy;
			oXmlHttp.send(sBody)
		    }
		}
		catch (e) {
		    alert(e.toString());
		}
	}	
}

function handleRequestStateChangeCopy(){
	if (oXmlHttp.readyState == 4) {
		if (oXmlHttp.status == 200) {
			//alert("ok");
		}
		else {
			alert("Une erreur est survenue: " + oXmlHttp.statusText);
		}
	}
}

function getRequestBodyCopy(oForm) {
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



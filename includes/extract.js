function extracttrigger(ob){

        post_formExtract(document.getElementById("frmextract"));

}

function post_formExtract(f){
        //var oXmlHttp = zXmlHttp.createRequest();
        var sBody = getRequestBodyCopy(f);
	//alert(sBody);
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
			var response = oXmlHttp.responseText;
			//alert(response);
			TransformXML(response);
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

function loadXMLDoc(dname) {
	if (window.XMLHttpRequest) {
		  xhttp=new XMLHttpRequest();
	} else {
		  xhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.open("GET",dname,false);
	xhttp.send("");
	return xhttp.responseXML;
}

function TransformXML(xml) {
	xsl=loadXMLDoc("includes/extract.xsl");
	xml=(new DOMParser()).parseFromString(xml, "text/xml");
	div=document.getElementById("extractresults");
	
	// code for IE
	if (window.ActiveXObject) {
		  ex=xml.transformNode(xsl);
		  div.innerHTML=ex;
	  } // code for Mozilla, Firefox, Opera, etc.
	else if (document.implementation && document.implementation.createDocument)
	  {
		xsltProcessor=new XSLTProcessor();
		xsltProcessor.importStylesheet(xsl);
		resultDocument = xsltProcessor.transformToFragment(xml,document);
		while (div.hasChildNodes() ) { div.removeChild( div.lastChild ); }
		div.appendChild(resultDocument);
	  }
}

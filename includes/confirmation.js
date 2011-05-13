function confirmation(mesg,dest){
	var yesno;
	yesno = confirm(mesg);
	if (yesno) {
		window.location = dest;
	};
}

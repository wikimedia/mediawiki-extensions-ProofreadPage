// Author : ThomasV - License : GPL

function proofreadPageSetup() {
	if(!self.proofreadPageViewURL) {
		return;
	}

	if(document.URL.indexOf("action=protect") > 0 || document.URL.indexOf("action=unprotect") > 0) return;
	if(document.URL.indexOf("action=delete") > 0 || document.URL.indexOf("action=undelete") > 0) return;
	if(document.URL.indexOf("action=watch") > 0 || document.URL.indexOf("action=unwatch") > 0) return;
	if(document.URL.indexOf("action=history") > 0 ) return;
	if(document.URL.indexOf("&diff=") > 0 ) return;

	var displayWidth = 400; //default value
	//quantization: displayWidth must be multiple of 100px
	if (parseInt(navigator.appVersion)>3) {
		if (navigator.appName=="Netscape") {
			displayWidth = 100*parseInt((window.innerWidth/2-70)/100);
		}
		if (navigator.appName.indexOf("Microsoft")!=-1) {
			displayWidth = 100*parseInt((document.body.offsetWidth/2-70)/100);
		}
	}

	if(displayWidth<proofreadPageWidth) { 
		image_url = proofreadPageThumbURL.replace('##WIDTH##',""+displayWidth); 
	}
	else { 
		image_url = proofreadPageViewURL; 
		displayWidth = proofreadPageWidth; 
	}

	//image 
	image = document.createElement("img");
	image.setAttribute("src", image_url); 
	image.setAttribute("style","padding:0;margin:0;border:0;");
	image.setAttribute("width",displayWidth);

	//container
	//useful for hooking elements to the image, eg href or zoom.
	container = document.createElement("div");
	container.setAttribute("id", "proofreadImage");
	container.appendChild(image);

	table = document.createElement("table");
	table.setAttribute("id", "textBoxTable");
	t_body = document.createElement("tbody");
	t_row = document.createElement("tr");
	t_row.setAttribute("valign","top");
	cell0 = document.createElement("td");   
	cell0.setAttribute("width", "50%");
	cell0.setAttribute("style", "padding-right: 0.5em");
	cell1 = document.createElement("td");   
	cell1.appendChild(container);
	cell1.setAttribute("valign","top");
	t_row.appendChild(cell0);
	t_row.appendChild(cell1);
	t_body.appendChild(t_row);
	table.appendChild(t_body);

	if(proofreadPageIsEdit) {
		text = document.getElementById("wpTextbox1"); 
		if (text) {
			text.setAttribute("style", "width:100%; height:100%;");  //width seems to be set by monobook already...
		}
	} else { 
		text = document.getElementById("bodyContent"); 
	}
	if(!text) return;

	f = text.parentNode; 
	new_text = f.removeChild(text);
	cell0.appendChild(new_text);
	copywarn = document.getElementById("editpage-copywarn");
	if(copywarn){ 
		f.insertBefore(table,copywarn);
	} else {
		f.appendChild(table);
	}
}
addOnloadHook(proofreadPageSetup);

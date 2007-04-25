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

	if(proofreadPageIsEdit) {
		text = document.getElementById("wpTextbox1"); 
		if (text) {
			text.setAttribute("style", "width:100%; height:100%;");  //width seems to be set by monobook already...

                        re = /^(\{\{PageQuality\|[0-9][0-9*]%\}\}|)<noinclude>([\s\S]*?)<\/noinclude>([\s\S]*?)(<noinclude>([\s\S]*?)<\/noinclude>|)\n$/;
                        m = text.value.match(re);
                        if(m) { 
                                pageHeader = m2[2]; 
                                pageBody   = m2[1]+m2[3];
                                pageFooter = m2[5]; 
                        }
                        else {
                                pageBody = text.value;
                                pageHeader = '';
                                pageFooter = '';
                        }
		}
	} else { 
		text = document.getElementById("bodyContent"); 
	}
	if(!text) return;




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
	cell_left = document.createElement("td");   
	cell_left.setAttribute("width", "50%");
	cell_left.setAttribute("style", "padding-right: 0.5em");
	cell_right = document.createElement("td");   
	cell_right.appendChild(container);
	cell_right.setAttribute("valign","top");
	cell_right.setAttribute("rowspan","3");
	t_row.appendChild(cell_left);
	t_row.appendChild(cell_right);
	t_body.appendChild(t_row);
	table.appendChild(t_body);

	f = text.parentNode; 
	new_text = f.removeChild(text);
        

	if(proofreadPageIsEdit) {
		cell_left.innerHTML = ''
			+'Header (noinclude):<br/>'
			+'<textarea name="headerTextbox" rows="4" cols="80">'+pageHeader+'</textarea>'
			+'<br/>Page body (to be transcluded):<br/>'
			+'<textarea name="wpTextbox1" id="wpTextbox1" rows="40" cols="80">'+pageBody+'</textarea>'
			+'<br/>Footer (noinclude):<br/>'
			+'<textarea name="footerTextbox" rows="4" cols="80">'+pageFooter+'</textarea>';

		saveButton = document.getElementById("wpSave"); 
		saveButton.setAttribute("onclick","proofreadPageFillForm(this.form);");
		previewButton = document.getElementById("wpPreview"); 
		previewButton.setAttribute("onclick","proofreadPageFillForm(this.form);");

	} else cell_left.appendChild(new_text);

	copywarn = document.getElementById("editpage-copywarn");
	if(copywarn){ 
		f.insertBefore(table,copywarn);
	} else {
		f.appendChild(table);
	}
}




function proofreadPageFillForm(form) {
	header = form.elements["headerTextbox"];
	footer = form.elements["footerTextbox"];
	if(header){
		h = header.value;
		if(h) h = "<noinclude>"+h+"</noinclude>";
		f = footer.value;
		if(f) f = "<noinclude>\n"+f+"</noinclude>";
		ph = header.parentNode; 
		ph.removeChild(header);
		pf = footer.parentNode; 
		pf.removeChild(footer);
		form.elements["wpTextbox1"].value = h+form.elements["wpTextbox1"].value+f;
	}
}


addOnloadHook(proofreadPageSetup);

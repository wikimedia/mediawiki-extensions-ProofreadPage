// Author : ThomasV - License : GPL


function proofreadpage_init_tabs(){

	a = document.getElementById("p-cactions");
	if (!a) return;
	b = a.getElementsByTagName("ul");
	if (!b) return;

	if(self.proofreadPageViewURL) {
		b[0].innerHTML = b[0].innerHTML 
			+ '<li id="ca-image">'
			+ '<a href="'+proofreadPageViewURL+'">'
			+ proofreadPageMessageImage+'</a></li>';
	}

	if(self.proofreadPageIndexURL){
		b[0].innerHTML = b[0].innerHTML 
			+ '<li id="ca-index">'
			+ '<a href="'+proofreadPageIndexURL+'" title="'+proofreadPageMessageIndex+'">'
			+ '<img src="'+wgScriptPath+'/extensions/ProofreadPage/uparrow.png" alt="'+proofreadPageMessageIndex+'" width="15" height="15" /></a></li>';
	}
  
	if(self.proofreadPageNextURL){
		b[0].innerHTML = 
			'<li id="ca-next">'
			+ '<a href="'+self.proofreadPageNextURL+'" title="'+proofreadPageMessageNextPage+'">'
			+ '<img src="'+wgScriptPath+'/extensions/ProofreadPage/rightarrow.png" alt="'+proofreadPageMessageNextPage+'" width="15" height="15" /></a></li>'
			+ b[0].innerHTML ;
	}

	if(self.proofreadPagePrevURL){
		b[0].innerHTML = 
			'<li id="ca-prev">'
			+ '<a href="'+self.proofreadPagePrevURL+'" title="'+proofreadPageMessagePrevPage+'">'
			+ '<img src="'+wgScriptPath+'/extensions/ProofreadPage/leftarrow.png" alt="'+proofreadPageMessagePrevPage+'" width="15" height="15" /></a></li>'
			+ b[0].innerHTML ;
       }
}





function proofreadpage_image_url(requested_width){

        if(self.proofreadPageExternalURL) {
		image_url = proofreadPageExternalURL; 
	}
	else {

		//enforce quantization: width must be multiple of 100px
		width = (100*requested_width)/100;

		if(width < proofreadPageWidth)  {
			 self.DisplayWidth = width;
			 self.DisplayHeight = width*proofreadPageHeight/proofreadPageWidth;
			 image_url = proofreadPageThumbURL.replace('##WIDTH##',""+width); 
		}
		else {
		     self.DisplayWidth = proofreadPageWidth;
		     self.DisplayHeight = proofreadPageHeight;
		     image_url = proofreadPageViewURL; 
		}
	}

	return image_url;
}



function proofreadpage_make_edit_area(container,text){

	re = /^<noinclude>([\s\S]*?)<\/noinclude>([\s\S]*)<noinclude>([\s\S]*?)<\/noinclude>\n$/;
	m = text.match(re);
	if(m) {
		pageHeader = m[1];
		pageBody   = m[2];
		pageFooter = m[3];
	}
	else {
		re2 = /^<noinclude>([\s\S]*?)<\/noinclude>([\s\S]*?)\n$/;
		m2 = text.match(re2);
		if(m2) {
			pageHeader = m2[1];
			pageBody   = m2[2];
			pageFooter = '';
		}
		else {
			pageHeader = '<div class="pagetext">\n\n\n';
			pageBody = text;
			pageFooter = '</div>';
		}
	}

        //escape & character
	pageBody = pageBody.split("&").join("&amp;")
	pageHeader = pageHeader.split("&").join("&amp;")
	pageFooter = pageFooter.split("&").join("&amp;")

	container.innerHTML = ''
		+'<div id="prp_header" style="display:none">'+proofreadPageMessageHeader+'<br/>'
		+'<textarea name="headerTextbox" rows="4" cols="80">'+pageHeader+'</textarea>'
		+'<br/>'+proofreadPageMessagePageBody+'<br/></div>'
		+'<textarea name="wpTextbox1" id="wpTextbox1" rows="40" cols="80">'+pageBody+'</textarea>'
		+'<div id="prp_footer" style="display:none"><br/>'+proofreadPageMessageFooter+'<br/>'
		+'<textarea name="footerTextbox" rows="4" cols="80">'+pageFooter+'</textarea></div>';

	saveButton = document.getElementById("wpSave"); 
	if(saveButton){
		saveButton.setAttribute("onclick","proofreadPageFillForm(this.form);");
		previewButton = document.getElementById("wpPreview"); 
		previewButton.setAttribute("onclick","proofreadPageFillForm(this.form);");
		diffButton = document.getElementById("wpDiff")
		diffButton.setAttribute("onclick","proofreadPageFillForm(this.form);");
	} 
	else {
	     container.firstChild.nextSibling.setAttribute("readonly","readonly");
	}

}


function proofreadpage_toggle_visibility() {

	box = document.getElementById("wpTextbox1");
	h = document.getElementById("prp_header"); 
	f = document.getElementById("prp_footer"); 
	s = h.getAttribute("style");
	if( s == ""){
	     	h.setAttribute("style", "display:none;"); 
		f.setAttribute("style", "display:none;"); 
		if(self.TextBoxHeight)	box.setAttribute("style","height:"+(TextBoxHeight-7)+"px");
	} else {
	     	h.setAttribute("style", ""); 
		f.setAttribute("style", ""); 
		if(self.TextBoxHeight)  box.setAttribute("style","height:"+(TextBoxHeight-270)+"px");
	}
}


function proofreadpage_default_setup() {

	self.displayWidth = 400; //default value

	if (parseInt(navigator.appVersion)>3) {
		if (navigator.appName=="Netscape") {
			displayWidth = parseInt(window.innerWidth/2-70);
		}
		if (navigator.appName.indexOf("Microsoft")!=-1) {
			displayWidth = parseInt(document.body.offsetWidth/2-70);
		}
	}

        image_url = proofreadpage_image_url(displayWidth);

	if(self.DisplayHeight) self.TextBoxHeight = DisplayHeight;
	else self.TextBoxHeight = 700;

	if(proofreadPageIsEdit) {
		text = document.getElementById("wpTextbox1"); 
		if (!text) return;
	}
	else { 
		text = document.getElementById("bodyContent"); 
		if(!text) return;
	}


	//image 
	image = document.createElement("img");
	image.setAttribute("src", image_url); 
	image.setAttribute("style","padding:0;margin:0;border:0;");

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
		proofreadpage_make_edit_area(cell_left,new_text.value);
		toolbar = document.getElementById("toolbar");
		if(toolbar){
			var image = document.createElement("img");
			image.width = 23;
			image.height = 22;
			image.className = "mw-toolbar-editbutton";
			image.src = wgScriptPath+'/extensions/ProofreadPage/button_category_plus.png';
			image.border = 0;
			image.alt = proofreadPageMessageToggleHeaders;
			image.title = proofreadPageMessageToggleHeaders;
			image.style.cursor = "pointer";
			image.onclick = proofreadpage_toggle_visibility;
			toolbar.appendChild(image);
		}
		copywarn = document.getElementById("editpage-copywarn");
		f.insertBefore(table,copywarn);
		document.getElementById("wpTextbox1").setAttribute("style","height:"+(TextBoxHeight-7)+"px");

	} else {
		cell_left.appendChild(new_text);
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






/*
 *  Mouse Zoom.  Credits: http://valid.tjp.hu/zoom/
 */


var zp_clip;  //zp_clip is the large image
var zp_container;

var zoomamount_h=2; 
var zoomamount_w=2; 
var zoom_status=''; 

var ieox=0; var ieoy=0; 
var ffox=0; var ffoy=0;


//mouse move
function zoom_move(evt) {

	if(zoom_status != 1) { return false;}

	if(typeof(evt) == 'object') {
		var evt = evt?evt:window.event?window.event:null; if(!evt){ return;}
		if(evt.pageX) {
			xx=evt.pageX - ffox;
			yy=evt.pageY - ffoy;
		} 
		else {
			if(typeof(document.getElementById("proofreadImage")+1) == 'number') {return true;} 
			xx=evt.clientX - ieox;
			yy=evt.clientY - ieoy;
		}
	}
	else { 
		xx = lastxx; 
		yy = lastyy; 
	}
	lastxx = xx; 
        lastyy = yy;

	//new
        zp_clip.style.margin = 
                  ((yy > objh )?(objh*(1-zoomamount_h)):(yy*(1-zoomamount_h))) + 'px 0px 0px '
		+ ((xx > objw )?(objw*(1-zoomamount_w)):(xx*(1-zoomamount_w)))
		+ 'px';

	return false;
}






function zoom_off() {
	zp_container.style.width='0px';
	zp_container.style.height='0px';
	zoom_status = 0;
}




function countoffset() {
	zme=document.getElementById("proofreadImage");
	ieox=0; ieoy=0;
	for(zmi=0;zmi<50;zmi++) {
		if(zme+1 == 1) { 
			break;
		} 
		else {
			ieox+=zme.offsetLeft; 
			ieoy+=zme.offsetTop;
		}
		zme=zme.offsetParent; 
	}
	ffox=ieox;
	ffoy=ieoy;
	ieox-=document.body.scrollLeft;
	ieoy-=document.body.scrollTop;
}




function zoom_mouseup(evt) {

	 var evt = evt?evt:window.event?window.event:null; 
	 if(!evt) return;

	 //only left button
	 if(evt.button != 0) return;


	if(zoom_status == 0) {
       		zoom_on(evt);
		return false;
		}
	 else if(zoom_status == 1) {
		zoom_status = 2;
		return false;
	 }
	 else if(zoom_status == 2) {
	 	zoom_off(); 
		return false;
	 }
}


function zoom_on(evt) {

	var evt = evt?evt:window.event?window.event:null; if(!evt){ return;}
	zoom_status=1;

	if(evt.pageX) {
		countoffset();
		lastxx=evt.pageX - ffox; 
		lastyy=evt.pageY - ffoy;
		} 
	else {
		countoffset();
		lastxx=evt.clientX - ieox;
		lastyy=evt.clientY - ieoy; 
	}

	zoomamount_h = zp_clip.height/objh;
	zoomamount_w = zp_clip.width/objw;

        zp_container.style.margin = '0px 0px 0px 0px';
	zp_container.style.width = objw+'px';
	zp_container.style.height = objh+'px';

	zoom_move('');
	return false;
}


function proofreadPageZoom(){

	if(navigator.appName == "Microsoft Internet Explorer") return;
	if(!self.proofreadPageViewURL) return;
	if(self.DisplayWidth>800) return;

	zp = document.getElementById("proofreadImage");
	if(zp){
		hires_url = proofreadpage_image_url(800);
		self.objw = zp.firstChild.width;
		self.objh = zp.firstChild.height;

		zp.setAttribute("onmouseup","zoom_mouseup(event);" );
		zp.setAttribute("onmousemove","zoom_move(event);" );

		zp_container = document.createElement("div");
		zp_container.setAttribute("style","position:absolute; width:0; height:0; overflow:hidden;"); 
		zp_clip = document.createElement("img");
		zp_clip.setAttribute("src", hires_url);
		zp_clip.setAttribute("style", "padding:0;margin:0;border:0;");
		zp_container.appendChild(zp_clip);
		zp.insertBefore(zp_container,zp.firstChild); 

	}
}




function proofreadpage_init() {

	if( document.getElementById("proofreadImage")) return;

	if(document.URL.indexOf("action=protect") > 0 || document.URL.indexOf("action=unprotect") > 0) return;
	if(document.URL.indexOf("action=delete") > 0 || document.URL.indexOf("action=undelete") > 0) return;
	if(document.URL.indexOf("action=watch") > 0 || document.URL.indexOf("action=unwatch") > 0) return;
	if(document.URL.indexOf("action=history") > 0 ) return;

	/*check if external url is provided*/				       
	if(!self.proofreadPageViewURL) {
		text = document.getElementById("wpTextbox1"); 
		if (text) {
			proofreadPageIsEdit = true;
			re = /<span class="hiddenStructure" id="pageURL">\[http:\/\/(.*?)\]<\/span>/;
			m = re.exec(text.value);
			if( m ) { 
				self.proofreadPageExternalURL = "http://"+m[1];  
			}
		} 
		else {
			proofreadPageIsEdit = false;
			text = document.getElementById("bodyContent"); 
			try { 
				a = document.getElementById("pageURL");
				b = a.firstChild;
				self.proofreadPageExternalURL = b.getAttribute("href");
			} catch(err){};
		}
		//set to dummy values, not used
		self.proofreadPageWidth = 400;
		self.proofreadPageHeight = 400;
	}

	if(!self.proofreadPageViewURL && !self.proofreadPageExternalURL) return;

	if( self.proofreadpage_setup ) 

	     proofreadpage_setup(
		proofreadPageWidth,
		proofreadPageHeight, 
		proofreadPageIsEdit);

	else proofreadpage_default_setup();
}



addOnloadHook(proofreadpage_init);
addOnloadHook(proofreadpage_init_tabs);
hookEvent("load", proofreadPageZoom);

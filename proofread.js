// Author : ThomasV - License : GPL


function proofreadPageDoTabs(){

	a = document.getElementById("p-cactions");
	if (!a) return;
	b = a.getElementsByTagName("ul");
	if (!b) return;

	if(self.proofreadPageViewURL) {
		b[0].innerHTML = b[0].innerHTML 
			+ '<li id="ca-image">'
			+ '<a href='+proofreadPageViewURL+'>'
			+ 'Image</a></li>';
	}

	if(self.proofreadPageIndexURL){
		b[0].innerHTML = b[0].innerHTML 
			+ '<li id="ca-index">'
			+ '<a href='+proofreadPageIndexURL+' title="Index">'
			+ "<img src='http://upload.wikimedia.org/wikipedia/commons/a/af/1uparrow.png' alt='Index' width='15' height='15' longdesc='Next Page'/></a></li>";
	}
  
	if(self.proofreadPageNextURL){
		b[0].innerHTML = 
			'<li id="ca-next">'
			+ '<a href='+self.proofreadPageNextURL+' title="Next Page">'
			+ "<img src='http://upload.wikimedia.org/wikipedia/commons/3/3c/1rightarrow.png' alt='Next Page' width='15' height='15' longdesc='Next Page'/></a></li>"
			+ b[0].innerHTML ;
	}

	if(self.proofreadPagePrevURL){
		b[0].innerHTML = 
			'<li id="ca-prev">'
			+ '<a href='+self.proofreadPagePrevURL+' title="Previous Page">'
			+ "<img src='http://upload.wikimedia.org/wikipedia/commons/8/8e/1leftarrow.png' alt='Previous Page' width='15' height='15' longdesc='Previous Page'/></a></li>"
			+ b[0].innerHTML ;
       }
}





function proofreadPageSetup() {

	if( document.getElementById("proofreadImage")) return;
	if(!self.proofreadPageViewURL) return;

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
		if (!text) return;
		text.setAttribute("style", "width:100%; height:100%;");  //width seems to be set by monobook already...

		re = /^(\{\{PageQuality\|[0-9][0-9]%\}\}|)<noinclude>([\s\S]*?)<\/noinclude>([\s\S]*)<noinclude>([\s\S]*?)<\/noinclude>\n$/;
		m = text.value.match(re);
		if(m) {
			pageHeader = m[2];
			pageBody   = m[1]+m[3];
			pageFooter = m[4];
		}
		else {
			re2 = /^(\{\{PageQuality\|[0-9][0-9]%\}\}|)<noinclude>([\s\S]*?)<\/noinclude>([\s\S]*?)\n$/;
			m2 = text.value.match(re2);
			if(m2) {
				pageHeader = m2[2];
				pageBody   = m2[1]+m2[3];
				pageFooter = '';
			}
			else {
				pageHeader = '';
				pageBody = text.value;
				pageFooter = '';
			}
		}
	}
	else { 
		text = document.getElementById("bodyContent"); 
		if(!text) return;
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






/*
 *  Mouse Zoom.  Credits: http://valid.tjp.hu/zoom/
 */


//size of the zoom window
var zoomw=160;
var zoomh=120;

var zp_pic;
var zp_clip;
var zp_container;

var zoomamount=2; 
//size of the lowresself.pr image
var objw;
var objh;
var zoom_status=''; 
var zoomratio=zoomw/zoomh; 
var ieox=0; var ieoy=0; 
var ffox=0; var ffoy=0;


function zoom_move(evt) {

	if(zoom_status == 0) { return false;}

	if(typeof(evt) == 'object') {
		var evt = evt?evt:window.event?window.event:null; if(!evt){ return;}
		if(evt.pageX) {
			xx=evt.pageX - ffox;
			yy=evt.pageY - ffoy;
		} 
		else {
			if(typeof(document.getElementById("zp")+1) == 'number') {return true;} 
			xx=evt.clientX - ieox;
			yy=evt.clientY - ieoy;
		}
	} 
	else { 
		xx=lastxx; 
		yy=lastyy; 
	}
	lastxx=xx; lastyy=yy;

	zp_clip.style.margin=((yy-zoomh/2 > 0)?(zoomh/2-yy*zoomamount):(yy*(1-zoomamount)))+'px 0px 0px '+((xx-zoomw/2 > 0)?(zoomw/2-xx*zoomamount):(xx*(1-zoomamount)))+'px';

	zp_container.style.margin=((yy-zoomh/2 > 0)?(yy-zoomh/2):(0))+'px 0px 0px '+((xx-zoomw/2 > 0)?(xx-zoomw/2):(0))+'px';
	//width of the zoom window
	w2=((xx+zoomw/2<objw)?((zoomw/2<xx)?(zoomw):(zoomw/2+xx)):(zoomw/2+objw-xx));  if(w2<0) {w2=0;} 
	h2=((yy+zoomh/2<objh)?((zoomh/2<yy)?(zoomh):(zoomh/2+yy)):(zoomh/2+objh-yy));  if(h2<0) {h2=0;} 
	zp_container.style.width=w2+'px';
	zp_container.style.height=h2+'px';

	return false;
}

function zoom_off() {
	if(zoom_status == 1) {
		zp_container.style.width='0px';
		zp_container.style.height='0px';
	}
	zoom_status=0;
}

function countoffset() {
	zme=document.getElementById("zp");
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


function zoom_on(evt) {

	if(zoom_status==1) {
		zoom_off(); 
		return false;
	}

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

	objw=zp_pic.width; 
	objh=zp_pic.height; 
	zoomamount = zp_clip.height/objh;

	if(zoomw>objw) {zoomw=objw; zoomh=objw/zoomratio;}
	else if(zoomh>objh) {zoomh=objh; zoomw=objh*zoomratio}
	zoom_move('');
	return false;
}


function proofreadPageZoom(){

	if(navigator.appName == "Microsoft Internet Explorer") return;
	if(!self.proofreadPageViewURL) return;
	if(!self.proofreadPageWidth) return;

	zp = document.getElementById("proofreadImage");
	if(zp){
		if(proofreadPageWidth>800) {
			hires_url = proofreadPageThumbURL.replace('##WIDTH##',""+800); 
		}
		else { 
		     hires_url = proofreadPageViewURL; 
		}

	zp.setAttribute("onmouseup","zoom_on(event);" );
	zp.setAttribute("onmousemove","zoom_move(event);" );
	zp.setAttribute("id","zp" );
	zp_pic=zp.firstChild;

	zp_container = document.createElement("div");
	zp_container.setAttribute("style","position:absolute; width:0; height:0; overflow:hidden;"); 
	zp_clip = document.createElement("img");
	zp_clip.setAttribute("src", hires_url);
	zp_clip.setAttribute("style", "padding:0;margin:0;border:0;");
	zp_container.appendChild(zp_clip);
	zp.insertBefore(zp_container,zp_pic); 
	}
}



addOnloadHook(proofreadPageSetup);
addOnloadHook(proofreadPageDoTabs);
hookEvent("load", proofreadPageZoom);

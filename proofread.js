// Author : ThomasV - License : GPL

//todo : 
//empty pages : detect if textbox is empty


function pr_init_tabs(){

	var a = document.getElementById("p-cactions");
	if (!a) return;
	var b = a.getElementsByTagName("ul");
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




function pr_image_url(requested_width){
	var thumb_url;

	if(self.proofreadPageExternalURL) {
		thumb_url = proofreadPageViewURL;
		self.DisplayWidth = requested_width;
		self.DisplayHeight = "";
	}
	else {
		//enforce quantization: width must be multiple of 100px
		var width = (100*requested_width)/100;
		//compare to the width of the image
		if(width < proofreadPageWidth)  {
			thumb_url = proofreadPageThumbURL.replace('##WIDTH##',""+width); 
            self.DisplayWidth = requested_width;
            self.DisplayHeight = requested_width*proofreadPageHeight/proofreadPageWidth;
		}
		else {
		    thumb_url = proofreadPageViewURL; 
            self.DisplayWidth = proofreadPageWidth;
            self.DisplayHeight = proofreadPageHeight;
		}
	}
	return thumb_url;
}



function pr_make_edit_area(container,text){

	re = /^<noinclude>([\s\S]*?)\n*<\/noinclude>([\s\S]*)<noinclude>([\s\S]*?)<\/noinclude>\n$/;
	m = text.match(re);
	if(m) {
		pageHeader = m[1];
		pageBody   = m[2];
		pageFooter = m[3];
	}
	else {
		re2 = /^<noinclude>([\s\S]*?)\n*<\/noinclude>([\s\S]*?)\n$/;
		m2 = text.match(re2);
		if(m2) {
			pageHeader = m2[1];
			//apparently lookahead is not supported by all browsers
			//so let us do another regexp
			re3 = /^([\s\S]*?)<noinclude>([\s\S]*?)<\/noinclude>/;
			m3 = m2[2].match(re3);
			if(m3){
				pageBody   = m3[1];
				pageFooter = m3[2];
			}
			else{
				pageBody   = m2[2];
				pageFooter = '';
			}
		}
		else {
			pageHeader = '{{PageQuality|1|}}<div class="pagetext">';
			pageBody = text;
			pageFooter = '<references/></div>';
			document.editform.elements["wpSummary"].value="/* "+proofreadPageMessageQuality1+" */ ";
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


	var saveButton = document.getElementById("wpSave"); 
	var previewButton = document.getElementById("wpPreview"); 
	var diffButton = document.getElementById("wpDiff")
	if(saveButton){
		saveButton.onclick = pr_fill_form;
		previewButton.onclick = pr_fill_form;
		diffButton.onclick = pr_fill_form;
	} 
	else {
		//make the text area readonly
		container.firstChild.nextSibling.setAttribute("readonly","readonly");
	}
}


function pr_toggle_visibility() {

	var box = document.getElementById("wpTextbox1");
	var h = document.getElementById("prp_header"); 
	var f = document.getElementById("prp_footer"); 
	if( h.style.cssText == ''){
		h.style.cssText = 'display:none';
		f.style.cssText = 'display:none';
		if(self.vertHeight)	box.style.cssText = "height:"+(vertHeight-7)+"px";
	} else {
		h.style.cssText = '';
		f.style.cssText = '';
		if(self.vertHeight)  box.style.cssText = "height:"+(vertHeight-270)+"px";
	}
}

function pr_toggle_layout() {

	if (!self.pr_horiz)
		pr_fill_table(true);
	else
		pr_fill_table(false);
  
}





/*
 *  Mouse Zoom.  Credits: http://valid.tjp.hu/zoom/
 */

// global vars
var lastxx, lastyy, xx, yy;

var zp_clip;  //zp_clip is the large image
var zp_container;
var zp_img;

var zoomamount_h=2; 
var zoomamount_w=2; 
var zoomamount=2; 
var zoom_status=''; 

var ieox=0; var ieoy=0; 
var ffox=0; var ffoy=0;



/*relative coordinates of the mouse pointer*/
function get_xy(evt){

	if(typeof(evt) == 'object') {
		evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
		if(evt.pageX) {
			xx=evt.pageX - ffox;
			yy=evt.pageY - ffoy;
		}
		else {
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
    
}

//mouse move
function zoom_move(evt) {

	if(zoom_status != 1) { return false;}
	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
	get_xy(evt);
	zp_clip.style.margin =  ((yy > objh )?(objh*(1-zoomamount_h)):(yy*(1-zoomamount_h))) + 'px 0px 0px '
		+ ((xx > objw )?(objw*(1-zoomamount_w)):(xx*(1-zoomamount_w))) + 'px';
	return false;
}






function zoom_off() {
	zp_container.style.width='0px';
	zp_container.style.height='0px';
	zoom_status = 0;
}




function countoffset() {
	zme=document.getElementById("pr_container");
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

	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}

	//only left button; see http://unixpapa.com/js/mouse.html for why it is this complicated
	if(evt.which == null) {
		if(evt.button != 1) return false;
	} else {
		if(evt.which > 1) return false;
	}

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
	 return false;
}


function zoom_on(evt) {
	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
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


//zoom using two images (magnification glass)
function proofreadPageZoom(){

	if(!self.proofreadPageViewURL) return;
	if(self.DisplayWidth>800) return;

	zp = document.getElementById("pr_container");
	if(zp){
		var hires_url = pr_image_url(800);
		self.objw = zp.firstChild.width;
		self.objh = zp.firstChild.height;

		zp.onmouseup = zoom_mouseup;
		zp.onmousemove =  zoom_move;
		zp_container = document.createElement("div");
		zp_container.style.cssText ="position:absolute; width:0; height:0; overflow:hidden;";
		zp_clip = document.createElement("img");
		zp_clip.setAttribute("src", hires_url);
		zp_clip.style.cssText = "padding:0;margin:0;border:0;";
		zp_container.appendChild(zp_clip);
		zp.insertBefore(zp_container,zp.firstChild); 

	}
}



/********************************
 * new zoom : mouse wheel
 * 
 ********************************/

var margin_x = 0;
var margin_y = 0;
var prev_margin_x = 0;
var prev_margin_y = 0;
var init_x = 0;
var init_y = 0;


function pr_drop(evt){
	prev_margin_x = margin_x;
	prev_margin_y = margin_y;
	document.onmouseup = null;
	document.onmousemove = null;
	document.onmousedown = null;
	zp_container.onmousemove = pr_move;
	return false;
}

function pr_grab(evt){

	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
	zp_img = document.getElementById("ProofReadImage");
	zp_container = document.getElementById("pr_container");

	//only left button; see http://unixpapa.com/js/mouse.html for why it is this complicated
	if(evt.which == null) {
		if(evt.button != 1) return false;
	} else {
		if(evt.which > 1) return false;
	}

	document.onmousedown = function(){return false;}; 
	document.onmousemove = pr_drag;
	document.onmouseup = pr_drop;
	zp_container.onmousemove = pr_drag;
	
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
	
	init_x = lastxx;
	init_y = lastyy;
	
	return false;

}


function pr_move(evt) {
	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
	countoffset();
	get_xy(evt);
}

function pr_drag(evt) {
	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
	get_xy(evt);
	margin_x = prev_margin_x - (init_x-xx); 
	margin_y = prev_margin_y - (init_y-yy); 
	zp_img.style.margin =  margin_y + 'px 0px 0px ' + margin_x + 'px';
	image_container.style.cssText = self.container_css; //needed by IE6
	
	if (evt.preventDefault) evt.preventDefault();
	evt.returnValue = false;
	return false;
}


function pr_zoom(delta){

	image_container = document.getElementById("pr_container");
	zp_img = document.getElementById("ProofReadImage");		
	if(!zp_img) return;
	
	if (delta == 0) {
		zp_img.width = image_container.offsetWidth;
		zp_img.style.margin = '0px 0px 0px 0px';
		image_container.style.cssText = self.container_css; //needed by IE6

		prev_margin_x = margin_x = 0;
		prev_margin_y = margin_y = 0;
	}
	else{
		var old_width = zp_img.width;
		var new_width = Math.round(zp_img.width*Math.pow(1.1,delta));
		var delta_w = new_width - old_width;
		var s = (delta_w>0)?1:-1;

		for(var dw=s; dw != delta_w; dw=dw+s){
			zp_img.width = old_width + dw;//this adds 1 pixel
			image_container.style.cssText = self.container_css; //needed by IE6
			if(xx){
				//magnification factor
				var lambda = (old_width+dw)/old_width;
				margin_x = xx - lambda*(xx - prev_margin_x);
				margin_y = yy - lambda*(yy - prev_margin_y);
				zp_img.style.margin =  Math.round(margin_y) + 'px 0px 0px ' + Math.round(margin_x) + 'px';
			}
		}
		prev_margin_x = margin_x;
		prev_margin_y = margin_y;

	}
}

function pr_zoom_wheel(evt){
	evt = evt?evt:window.event?window.event:null; if(!evt){ return false;}
	var delta = 0;
	if (evt.wheelDelta) { 
		/* IE/Opera. */
		delta = evt.wheelDelta/120;
	}
	else if (evt.detail) {
		/** Mozilla case. */
		/** In Mozilla, sign of delta is different than in IE.
        	 * Also, delta is multiple of 3.
        	 */
		delta = -evt.detail/3;
	}
	if(delta) pr_zoom(delta);
	if(evt.preventDefault) evt.preventDefault();
	evt.returnValue = false;
}








/*do not use a table in the horizontal case ?*/
function  pr_fill_table(horizontal_layout){


	//remove existing image_container and table
	image_container = document.getElementById("pr_container");
	if(image_container) image_container.parentNode.removeChild(image_container);
	while(self.table.firstChild){
		self.table.removeChild(self.table.firstChild);
	}

	if(!proofreadPageIsEdit) horizontal_layout=false;

	//create image container
	var image_container = document.createElement("div");
	image_container.setAttribute("id", "pr_container");
	image_container.style.cssText = "background:#0000ff; overflow:hidden;";

	//setup the layout
	if(!horizontal_layout) {
		//use a table only here
		var t_table = document.createElement("table");
		var t_body = document.createElement("tbody");
		var cell_left  = document.createElement("td");
		var cell_right = document.createElement("td");
		t_table.appendChild(t_body);  

		var t_row = document.createElement("tr");
		t_row.setAttribute("valign","top");
		cell_left.style.cssText = "width:50%; padding-right:0.5em;";
		cell_right.setAttribute("rowspan","3");
		t_row.appendChild(cell_left);
		t_row.appendChild(cell_right);
		t_body.appendChild(t_row);

		cell_right.appendChild(image_container);
		cell_left.appendChild(self.text_container);
		self.table.appendChild(t_table);
	}
	else {
		self.table.appendChild(self.text_container);
		document.getElementById("contentSub").appendChild(image_container);
	}
	
	self.pr_horiz = horizontal_layout;

	//compute the dimensions of the image container
	if(!horizontal_layout){

		var desired_width = 400;
		if (parseInt(navigator.appVersion)>3) {
			if (navigator.appName.indexOf("Microsoft")!=-1) {
				desired_width = parseInt(document.body.offsetWidth/2-70);
			}
			else {
				desired_width = parseInt(window.innerWidth/2-70);
			}
		}
		//this sets DisplayWidth and DisplayHeight
		var thumb_url = pr_image_url(desired_width); 

		//self.DisplayHeight is known if the image is not external
		if(self.DisplayHeight) 
			self.vertHeight = self.DisplayHeight;
		else 
			self.vertHeight = 700;
	}
	else{
		if(document.selection  && !is_gecko)
			self.vertHeight = Math.ceil(document.body.clientHeight*0.4);
		else
			self.vertHeight = Math.ceil(window.innerHeight*0.4);
	}


	//fill the image container	
	if(!proofreadPageIsEdit) { 
		var image = document.createElement("img");
		image_container.appendChild(image);
		image.setAttribute("id", "ProofReadImage");
		image.setAttribute("src", thumb_url);
		image.setAttribute("width", self.DisplayWidth);
		image.style.cssText = "padding:0;margin:0;border:0;";
	}
	else{
		if(!horizontal_layout){
			img_w = self.DisplayWidth;
			self.container_css = "background:#000000; overflow:hidden; width:"+self.DisplayWidth+"px; height:"+self.vertHeight+"px;";
		}
		else{
			img_w = 0; //prevent the container from being resized when the image is downloaded. 
			self.container_css = "background:#000000; overflow-x:hidden; overflow-y:scroll; width:100%; height:"+self.vertHeight+"px;";
		}
		image_container.innerHTML = "<img id=\"ProofReadImage\" src=\""+proofreadPageViewURL+"\" width=\""+img_w+"\" />";
		image_container.style.cssText = self.container_css;
		document.getElementById("wpTextbox1").style.cssText = "height:"+self.vertHeight+"px";
		pr_zoom(0);
	}

	//setup the mouse wheel listener
	if(proofreadPageIsEdit) {
		if (image_container.addEventListener) image_container.addEventListener('DOMMouseScroll', pr_zoom_wheel, false);
		image_container.onmousewheel = pr_zoom_wheel;
		image_container.onmousedown = pr_grab;
		image_container.onmousemove = pr_move;

		zp_img = document.getElementById("ProofReadImage");
		zp_container = document.getElementById("pr_container");
	}
}







function pr_setup() {

	self.table = document.createElement("div");
	self.text_container = document.createElement("div");
	self.image_container = document.createElement("div");
	table.setAttribute("id", "textBoxTable");
	table.style.cssText = "width:100%;";

	//fill table
	if(self.proofreadpage_default_layout=='horizontal') 
		pr_fill_table(true);
	else
		pr_fill_table(false);

	//insert the image    
	if(proofreadPageIsEdit) {
		var text = document.getElementById("wpTextbox1"); 
	}
	else { 
		var text = document.getElementById("bodyContent"); 
	}
	if(!text) return;
	var f = text.parentNode; 
	var new_text = f.removeChild(text);

	if(proofreadPageIsEdit) {
		pr_make_edit_area(self.text_container,new_text.value);
		var copywarn = document.getElementById("editpage-copywarn");
		f.insertBefore(table,copywarn);
		
	}
	else {
		self.text_container.appendChild(new_text);
		f.appendChild(self.table);
	}
  
	//add buttons  
	if(proofreadPageIsEdit) {

		var toolbar = document.getElementById("toolbar");
		/*var f = tb.parentNode; 
		 var toolbar = f.removeChild(tb);
		 self.text_container.insertBefore(toolbar,self.text_container.firstChild);*/

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
			image.onclick = pr_toggle_visibility;
			toolbar.appendChild(image);
			
			var image3 = document.createElement("img");
			image3.width = 23;
			image3.height = 22;
			image3.border = 0;
			image3.className = "mw-toolbar-proofread";
			image3.style.cursor = "pointer";
			image3.alt = "-";
			image3.title = "zoom out";
			image3.src = wgScriptPath+"/extensions/ProofreadPage/Button_zoom_out.png";
			image3.onclick = new Function("pr_zoom(-2);");
			toolbar.appendChild(image3);
			
			var image4 = document.createElement("img");
			image4.width = 23;
			image4.height = 22;
			image4.border = 0;
			image4.className = "mw-toolbar-proofread";
			image4.style.cursor = "pointer";
			image4.alt = "-";
			image4.title = "reset zoom";
			image4.src = wgScriptPath+"/extensions/ProofreadPage/Button_examine.png";
			image4.onclick = new Function("pr_zoom(0);");
			toolbar.appendChild(image4);
			
			var image2 = document.createElement("img");
			image2.width = 23;
			image2.height = 22;
			image2.border = 0;
			image2.className = "mw-toolbar-proofread";
			image2.style.cursor = "pointer";
			image2.alt = "+";
			image2.title = "zoom in";
			image2.src = wgScriptPath+"/extensions/ProofreadPage/Button_zoom_in.png";
			image2.onclick = new Function("pr_zoom(2);");
			toolbar.appendChild(image2);
			
			var image1 = document.createElement("img");
			image1.width = 23;
			image1.height = 22;
			image1.className = "mw-toolbar-editbutton";
			image1.src = wgScriptPath+'/extensions/ProofreadPage/Button_multicol.png';
			image1.border = 0;
			image1.alt = " ";
			image1.title = "vertical/horizontal layout";
			image1.style.cursor = "pointer";
			image1.onclick = pr_toggle_layout;
			toolbar.appendChild(image1);

		}
	}
}




function pr_fill_form() {
	var form = document.getElementById("editform");
	var header = form.elements["headerTextbox"];
	var footer = form.elements["footerTextbox"];
	if(header){
		var h = header.value.replace(/(\s*(\r?\n|\r))+$/, ''); 
		if(h) h = "<noinclude>"+h+"\n\n\n</noinclude>";
		var f = footer.value;
		if(f) f = "<noinclude>\n"+f+"</noinclude>";
		var ph = header.parentNode; 
		ph.removeChild(header);
		var pf = footer.parentNode; 
		pf.removeChild(footer);
		form.elements["wpTextbox1"].value = h+form.elements["wpTextbox1"].value+f;
		form.elements["wpTextbox1"].setAttribute('readonly',"readonly");
	}
}




function pr_init() {

	if( document.getElementById("pr_container")) return;

	if(document.URL.indexOf("action=protect") > 0 || document.URL.indexOf("action=unprotect") > 0) return;
	if(document.URL.indexOf("action=delete") > 0 || document.URL.indexOf("action=undelete") > 0) return;
	if(document.URL.indexOf("action=watch") > 0 || document.URL.indexOf("action=unwatch") > 0) return;
	if(document.URL.indexOf("action=history") > 0 ) return;

	/*check if external url is provided*/				       
	if(!self.proofreadPageViewURL) {
		var text = document.getElementById("wpTextbox1"); 
		if (text) {
			var proofreadPageIsEdit = true;
			re = /<span class="hiddenStructure" id="pageURL">\[http:\/\/(.*?)\]<\/span>/;
			m = re.exec(text.value);
			if( m ) { 
				self.proofreadPageViewURL = "http://"+m[1];  
				self.proofreadPageExternalURL = true;
			}
		} 
		else {
			var proofreadPageIsEdit = false;
			text = document.getElementById("bodyContent"); 
			try { 
				var a = document.getElementById("pageURL");
				var b = a.firstChild;
				self.proofreadPageViewURL = b.getAttribute("href");
				self.proofreadPageExternalURL = true;
			} catch(err){};
		}
		//set to dummy values, not used
		self.proofreadPageWidth = 400;
		self.proofreadPageHeight = 400;
	}

	if(!self.proofreadPageViewURL) return;

	if( self.proofreadpage_setup ) {
	  
	    proofreadpage_setup(
		proofreadPageWidth,
		proofreadPageHeight, 
		proofreadPageIsEdit);

	}
	else pr_setup();
}



addOnloadHook(pr_init);
addOnloadHook(pr_init_tabs);


function pr_initzoom(){
	if(document.getElementById("wpTextbox1")){
		if(self.pr_horiz)
			document.getElementById("wpTextbox1").style.cssText = "height:"+self.vertHeight+"px";
		else
			document.getElementById("wpTextbox1").style.cssText = "height:"+(self.vertHeight-7)+"px";
		pr_zoom(0);
	}
	else proofreadPageZoom();
	
}
hookEvent("load", pr_initzoom );


/*Quality buttons*/

function pr_add_quality(form,value){
 
	var text="";
	switch(value){
		case 0: text = proofreadPageMessageQuality0; break;
		case 1: text = proofreadPageMessageQuality1; break;
		case 2: text = proofreadPageMessageQuality2; break;
		case 3: text = proofreadPageMessageQuality3; break;
		case 4: text = proofreadPageMessageQuality4; break;
	}

	form.elements["wpSummary"].value="/* "+text+" */ ";
	s = form.elements["headerTextbox"].value;
	s = s.replace(/\{\{PageQuality\|(.*?)\}\}/gi,"")
	form.elements["headerTextbox"].value="{{PageQuality|"+value+"|"+wgUserName+"}}"+s;
	//remove template from wpTextbox1 in case it was corrupted
	s = form.elements["wpTextbox1"].value;
	s = s.replace(/\{\{PageQuality\|(.*?)\}\}/gi,"")
	form.elements["wpTextbox1"].value=s;
}


function pr_add_quality_buttons(){

    if(self.proofreadpage_no_quality_buttons) return;

	var ig  = document.getElementById("wpWatchthis");
	if(!ig) return;

	var s = document.editform.headerTextbox.value;
	var reg = /\{\{PageQuality\|([0-9]*(%|))(\|.*?|)\}\}/g;
	var m = reg.exec(s);
	var show4 = false;
	if(m) {
		//this is for backward compatibility
		if(m[1]=="100%") m[1]="4";
		if(m[1]=="75%") m[1]="3";
		if(m[1]=="50%") m[1]="1";
		if(m[1]=="25%") m[1]="2";

		if( (m[3] != "|"+wgUserName) && (m[1]=="3")) show4 = true;
		if(m[1] =="4") show4 = true;
	}
	var f = document.createElement("span");
	f.innerHTML = 
' <span class="quality0"> <input type="radio" name="quality" onclick="pr_add_quality(this.form,0)"> </span>'
+'<span class="quality2"> <input type="radio" name="quality" onclick="pr_add_quality(this.form,2)"> </span>'
+'<span class="quality1"> <input type="radio" name="quality" onclick="pr_add_quality(this.form,1)"> </span>'
+'<span class="quality3"> <input type="radio" name="quality" onclick="pr_add_quality(this.form,3)"> </span>';
	if(show4) f.innerHTML = f.innerHTML 
+ '<span class="quality4"> <input type="radio" name="quality" onclick="pr_add_quality(this.form,4)"> </span>';
	f.innerHTML = f.innerHTML + '&nbsp;'+proofreadPageMessageStatus;
	ig.parentNode.insertBefore(f,ig.nextSibling.nextSibling.nextSibling);
	if(m) { 
		switch(m[1]){
			case "4": document.editform.quality[4].checked=true; break;
			case "3": document.editform.quality[3].checked=true; break;
			case "1": document.editform.quality[2].checked=true; break; 
			case "2": document.editform.quality[1].checked=true; break; 
			case "0": document.editform.quality[0].checked=true; break; 
		}
	}
}

 

addOnloadHook(pr_add_quality_buttons);

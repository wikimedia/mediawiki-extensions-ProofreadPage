// Author : ThomasV - License : GPL

function prInitTabs() {
	$( '#ca-talk' ).prev().before( '<li id="ca-prev"><span>' + self.proofreadPagePrevLink + '</span></li>' );
	$( '#ca-talk' ).prev().before( '<li id="ca-next"><span>' + self.proofreadPageNextLink + '</span></li>' );
	$( '#ca-talk' ).after( '<li id="ca-index"><span>' + self.proofreadPageIndexLink + '</span></li>' );
	$( '#ca-talk' ).after( '<li id="ca-image"><span>' + self.proofreadPageScanLink + '</span></li>' );
}

function prSetSummary() {
	jQuery( "input[name='wpQuality']" ).click( function() {
		var text = mediaWiki.msg( 'proofreadpage_quality' + this.value + '_category' );
		document.editform.elements[ 'wpSummary' ].value = '/* ' + text + ' */ ';
	});

}

function prHideHeaderFooter() {
	jQuery( "button[name='hideHeader']" ).click( function() {
		if ( jQuery( "textarea[id='wpHeaderTextbox']" ).is( ":visible" ) ) {
			jQuery( "textarea[id='wpHeaderTextbox']" ).hide();
			jQuery( "button[name='hideHeader']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		} else {
			jQuery( "textarea[id='wpHeaderTextbox']" ).show();
			jQuery( "button[name='hideHeader']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		}
	} );
	jQuery( "button[name='hideFooter']" ).click( function() {
		if ( jQuery( "textarea[id='wpFooterTextbox']" ).is(":visible") ) {
			jQuery( "textarea[id='wpFooterTextbox']" ).hide();
			jQuery( "button[name='hideFooter']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		} else {
			jQuery( "textarea[id='wpFooterTextbox']" ).show();
			jQuery( "button[name='hideFooter']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		}
	} );
}

function prResetSize() {
	var box = document.getElementById( 'wpTextbox1' );
	var h = document.getElementById( 'prp_header' );
	var f = document.getElementById( 'prp_footer' );
	if( h.style.cssText == 'display:none;' ) {
		box.style.cssText = 'height:' + ( self.DisplayHeight - 6 ) + 'px';
	} else {
		if( self.pr_horiz ) {
			box.style.cssText = 'height:' + ( self.DisplayHeight - 6 ) + 'px';
		} else {
			box.style.cssText = 'height:' + ( self.DisplayHeight - 6 - h.offsetHeight - f.offsetHeight ) + 'px';
		}
	}
}

function prToggleVisibility() {
	var box = document.getElementById( 'wpTextbox1' );
	var h = document.getElementById( 'prp_header' );
	var f = document.getElementById( 'prp_footer' );
	if( h.style.cssText == '' ) {
		h.style.cssText = 'display:none';
		f.style.cssText = 'display:none';
	} else {
		h.style.cssText = '';
		f.style.cssText = '';
	}
	prResetSize();
}

function prToggleLayout() {
	self.pr_horiz = ! self.pr_horiz;
	prFillTable();
	prResetSize();
	prZoom( 0 );
}

/*
 * Mouse Zoom.  Credits: http://valid.tjp.hu/zoom/
 */

// global vars
var lastxx, lastyy, xx, yy;

var zp_clip; // zp_clip is the large image
var zp_container;

var zoomamount_h = 2;
var zoomamount_w = 2;
var zoomamount = 2;
var zoom_status = '';

var ieox = 0;
var ieoy = 0;
var ffox = 0;
var ffoy = 0;

/* relative coordinates of the mouse pointer */
function getXy( evt ) {
	if( typeof( evt ) == 'object' ) {
		evt = evt ? evt : window.event ? window.event : null;
		if( !evt ) {
			return false;
		}
		if( evt.pageX ) {
			xx = evt.pageX - ffox;
			yy = evt.pageY - ffoy;
		} else {
			xx = evt.clientX - ieox;
			yy = evt.clientY - ieoy;
		}
	} else {
		xx = lastxx;
		yy = lastyy;
	}
	lastxx = xx;
	lastyy = yy;
}

// mouse move
function zoomMove( evt ) {
	if( zoom_status != 1 ) {
		return false;
	}
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	getXy( evt );
	zp_clip.style.margin = ( ( yy > objh ) ? ( objh * ( 1 - zoomamount_h ) ) :
		( yy * ( 1 - zoomamount_h ) ) ) + 'px 0px 0px ' +
		( ( xx > objw ) ? ( objw * ( 1 - zoomamount_w ) ) :
		( xx * ( 1 - zoomamount_w ) ) ) + 'px';
	return false;
}

function zoomOff() {
	zp_container.style.width = '0px';
	zp_container.style.height = '0px';
	zoom_status = 0;
}

function countoffset() {
	zme = document.getElementById( 'pr_container' );
	ieox = 0;
	ieoy = 0;
	for( zmi = 0; zmi < 50; zmi++ ) {
		if( zme+1 == 1 ) {
			break;
		} else {
			ieox += zme.offsetLeft;
			ieoy += zme.offsetTop;
		}
		zme=zme.offsetParent;
	}
	ffox = ieox;
	ffoy = ieoy;
	ieox -= document.body.scrollLeft;
	ieoy -= document.body.scrollTop;
}

function zoomMouseup( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}

	// only left button; see http://unixpapa.com/js/mouse.html for why it is this complicated
	if( evt.which == null) {
		if( evt.button != 1 ) {
			return false;
		}
	} else {
		if( evt.which > 1 ) {
			return false;
		}
	}

	if( zoom_status == 0 ) {
		zoomOn( evt );
		return false;
	} else if( zoom_status == 1 ) {
		zoom_status = 2;
		return false;
	} else if( zoom_status == 2 ) {
		zoomOff();
		return false;
	}
	return false;
}

function zoomOn( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	zoom_status = 1;

	if( evt.pageX ) {
		countoffset();
		lastxx = evt.pageX - ffox;
		lastyy = evt.pageY - ffoy;
	} else {
		countoffset();
		lastxx = evt.clientX - ieox;
		lastyy = evt.clientY - ieoy;
	}

	zoomamount_h = zp_clip.height / objh;
	zoomamount_w = zp_clip.width / objw;

	zp_container.style.width = objw + 'px';
	zp_container.style.height = objh + 'px';
	zp_clip.style.margin = ( ( lastyy > objh ) ? ( objh * ( 1 - zoomamount_h ) ) :
		( lastyy * ( 1 - zoomamount_h ) ) ) + 'px 0px 0px ' +
		( ( lastxx > objw ) ? ( objw * ( 1 - zoomamount_w ) ) : ( lastxx * ( 1 - zoomamount_w ) ) ) + 'px';

	return false;
}

//zoom using two images (magnification glass)
function prInitZoom( width, height ) {
	var maxWidth = 800;

	if( width > maxWidth ) {
		return;
	}

	zp = document.getElementById( 'pr_container' );
	if( !zp ) {
		return;
	}
	prFetchThumbUrl( maxWidth, function( largeUrl, largeWidth, largeHeight ) {
		self.objw = width;
		self.objh = height;

		zp.onmouseup = zoomMouseup;
		zp.onmousemove =  zoomMove;
		zp_container = document.createElement( 'div' );
		zp_container.style.cssText = 'position:absolute; width:0; height:0; overflow:hidden;';
		zp_clip = document.createElement( 'img' );
		zp_clip.setAttribute( 'src', largeUrl );
		zp_clip.style.cssText = 'padding:0;margin:0;border:0;';
		zp_container.appendChild( zp_clip );
		zp.insertBefore( zp_container, zp.firstChild );
	} );
}

/********************************
 * new zoom : mouse wheel
 *
 ********************************/

/* width and margin of the scan */
var margin_x = 0;
var margin_y = 0;
var img_width = 0;

/* initial mouse position during a drag */
var init_x = 0;
var init_y = 0;

var is_drag = false;
var is_zoom = false;
var pr_container = false;
var pr_rect = false;

/* size of the window */
var pr_width = 0, pr_height = 0;

function setContainerCss( show_scrollbars ) {
	var sl = pr_container.scrollLeft; // read scrollbar values
	var st = pr_container.scrollTop;
	if( show_scrollbars ) {
		self.container_css = self.container_css.replace( 'overflow:hidden', 'overflow:auto' );
		self.container_css = self.container_css.replace( 'cursor:crosshair', 'cursor:default' );
		// we should check if the sb is going to be shown
		if( margin_x < 0 ) {
			sl = - Math.round( margin_x );
			margin_x = 0;
		}
		if( margin_y < 0 ) {
			st = - Math.round( margin_y );
			margin_y = 0;
		}
	} else {
		self.container_css = self.container_css.replace( 'overflow:auto', 'overflow:hidden' );
		self.container_css = self.container_css.replace( 'cursor:default', 'cursor:crosshair' );
		if( sl ) {
			margin_x -= sl;
			sl = 0;
		}
		if( st ) {
			margin_y -= st;
			st = 0;
		}
	}
	prSetMargins( margin_x, margin_y, false );
	pr_container.scrollLeft = sl;
	pr_container.scrollTop = st;
}

function prDrop( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	getXy( evt );
	if( xx > pr_container.offsetWidth - 20 || yy > pr_container.offsetHeight - 20 ) {
		return false;
	}

	document.onmouseup = null;
	document.onmousemove = null;
	document.onmousedown = null;
	pr_container.onmousemove = prMove;
	if( is_drag == false ) {
		is_zoom = !is_zoom;
	} else {
		if( is_zoom ) {
			is_zoom = false;
			if( boxWidth * boxWidth + boxHeight * boxHeight >= 2500 ) {
				var ratio_x = Math.abs( pr_container.offsetWidth / self.boxWidth );
					prSetMargins(
						( margin_x - xMin ) * ratio_x,
						( margin_y - yMin ) * ratio_x,
						img_width * ratio_x
					);
			}
		}
	}
	is_drag = false;
	pr_rect.style.cssText = "display:none";
	setContainerCss(!is_zoom);
	return false;
}

function prGrab( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	getXy( evt );
	if( xx > pr_container.offsetWidth - 20 || yy > pr_container.offsetHeight - 20 ) {
		return false;
	}

	// only left button; see http://unixpapa.com/js/mouse.html for why it is this complicated
	if( evt.which == null ) {
		if( evt.button != 1 ) {
			return false;
		}
	} else {
		if( evt.which > 1 ) {
			return false;
		}
	}

	document.onmousedown = function() { return false; };
	document.onmousemove = prDrag;
	document.onmouseup = prDrop;
	pr_container.onmousemove = prDrag;

	if( evt.pageX ) {
		countoffset();
		lastxx = evt.pageX - ffox;
		lastyy = evt.pageY - ffoy;
	} else {
		countoffset();
		lastxx = evt.clientX - ieox;
		lastyy = evt.clientY - ieoy;
	}

	if( is_zoom ) {
		init_x = - margin_x + lastxx;
		init_y = - margin_y + lastyy;
	} else {
		init_x = pr_container.scrollLeft + lastxx;
		init_y = pr_container.scrollTop + lastyy;
	}
	is_drag = false;
	return false;
}

function prMove( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	countoffset();
	getXy(evt);
}

function prDrag( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	getXy( evt );
	if( xx > pr_container.offsetWidth - 20 || yy > pr_container.offsetHeight - 20 ) {
		return false;
	}
	if( !is_zoom ) {
		pr_container.scrollLeft = ( init_x - xx );
		pr_container.scrollTop  = ( init_y - yy );
	} else {
		self.xMin = Math.min( init_x + margin_x, xx );
		self.yMin = Math.min( init_y + margin_y, yy );
		self.xMax = Math.max( init_x + margin_x, xx );
		self.yMax = Math.max( init_y + margin_y, yy );
		self.boxWidth  = Math.max( xMax-xMin, 1 );
		self.boxHeight = Math.max( yMax-yMin, 1 );
		if( boxWidth * boxWidth + boxHeight * boxHeight < 2500 ) {
			pr_rect.style.cssText = 'display:none;';
		} else {
			ratio = pr_container.offsetWidth / pr_container.offsetHeight;
			if( boxWidth / boxHeight < ratio ) {
				boxWidth = boxHeight * ratio;
				if( xx == xMin ) {
					xMin = init_x + margin_x - boxWidth;
				}
			} else {
				boxHeight = boxWidth / ratio;
				if( yy == yMin ) {
					yMin = init_y + margin_y - boxHeight;
				}
			}
			pr_rect.style.cssText = 'cursor:crosshair;opacity:0.5;position:absolute;left:' +
				xMin + 'px;top:' + yMin + 'px;width:' + boxWidth + 'px;height:' +
				boxHeight + 'px;background:#000000;';
		}
	}
	if ( evt.preventDefault ) {
		evt.preventDefault();
	}
	evt.returnValue = false;
	is_drag = true;
	return false;
}


function prSetMargins( mx, my, new_width ) {
	var zp_img = document.getElementById( 'ProofReadImage' );
	if( zp_img ) {
		margin_x = mx;
		margin_y = my;
		zp_img.style.margin = Math.round( margin_y ) + 'px 0px 0px ' + Math.round( margin_x ) + 'px';
		if( new_width ) {
			img_width = Math.round( new_width );
			zp_img.width = img_width;
		}
		pr_container.style.cssText = self.container_css; // needed by IE6
	}
}

self.prZoom = function( delta ) {
	if ( delta == 0 ) {
		// reduce width by 20 pixels in order to prevent horizontal scrollbar
		// from showing up
		prSetMargins( 0, 0, pr_container.offsetWidth - 20 );
	} else {
		var old_margin_x = margin_x;
		var old_margin_y = margin_y;
		var old_width = img_width;
		var new_width = Math.round( old_width * Math.pow( 1.1, delta ) );
		var delta_w = new_width - old_width;
		if( delta_w == 0 ) {
			return;
		}
		var s = ( delta_w > 0 ) ? 1 : -1;
		for( var dw = s; dw != delta_w; dw = dw + s ) {
			var lambda = ( old_width + dw ) / old_width;
			prSetMargins(
				xx - lambda * ( xx - old_margin_x ),
				yy - lambda * ( yy - old_margin_y ),
				old_width + dw
			);
		}
	}
};

function prZoomWheel( evt ) {
	evt = evt ? evt : window.event ? window.event : null;
	if( !evt ) {
		return false;
	}
	var delta = 0;
	if ( evt.wheelDelta ) {
		/* IE/Opera. */
		delta = evt.wheelDelta / 120;
	} else if ( evt.detail ) {
		/**
		 * Mozilla case.
		 * In Mozilla, sign of delta is different than in IE.
		 * Also, delta is multiple of 3.
		 */
		delta = -evt.detail / 3;
	}
	if( is_zoom && delta ) {
		if( !self.proofreadpage_disable_wheelzoom ) {
			prZoom( delta );
		}
		if( evt.preventDefault ) {
			evt.preventDefault();
		}
		evt.returnValue = false;
	}
}

/**
 * Parses links included in a message already escape. Doesn't support cross-wiki links
 * @param message string The message
 * @return string
 */
function prParseLink( message ) {
	function replaceInternal( p0, p1, p2 ) {
		var text = '<a title="' + p1 + '" href="' + mw.util.wikiGetlink( p1 ) + '">';
		if( p2 != '' ) {
			text += p2;
		} else {
			text += p1;
		}
		return text + '</a>';
	}
	return message.replace( /\[\[([^\|]*)\|?([^\]]*)\]\]/g, replaceInternal );
}

/* fill table with textbox and image */
function prFillTable() {
	// remove existing table
	while( self.table.firstChild ) {
		self.table.removeChild( self.table.firstChild );
	}

	// setup the layout
	if( !pr_horiz ) {
		// use a table only here
		var t_table = document.createElement( 'table' );
		t_table.style.cssText = 'width: 100%; border-collapse: collapse; border-spacing: 0; border: none';
		var t_body = document.createElement( 'tbody' );
		var cell_left  = document.createElement( 'td' );
		var cell_right = document.createElement( 'td' );
		t_table.appendChild( t_body );

		var t_row = document.createElement( 'tr' );
		t_row.setAttribute( 'valign', 'top' );
		cell_left.style.cssText = 'width:50%; padding-right:0.5em; vertical-align:top; padding-left: 0';
		cell_right.setAttribute( 'rowspan', '3' );
		cell_right.style.cssText = 'vertical-align:top;';
		t_row.appendChild( cell_left );
		t_row.appendChild( cell_right );
		t_body.appendChild( t_row );
		cell_right.appendChild( pr_container_parent );
		cell_left.appendChild( self.text_container );
		self.table.appendChild( t_table );
	} else {
		self.table.appendChild( self.text_container );
		form = document.getElementById( 'editform' );
		tb = document.getElementById( 'toolbar' );
		if( form ) {
			form.parentNode.insertBefore( pr_container_parent, form );
		} else {
			self.table.insertBefore( pr_container_parent, self.table.firstChild );
		}
	}

	if( proofreadPageIsEdit ) {
		if( !pr_horiz ) {
			self.DisplayHeight = Math.ceil( pr_height * 0.85);
			self.DisplayWidth = parseInt( pr_width / 2 - 70 );
			css_wh = 'width:' + self.DisplayWidth + 'px; height:' + self.DisplayHeight + 'px;';
			pr_container_parent.style.cssText = 'position:relative; width:' + self.DisplayWidth + 'px;';
		} else {
			self.DisplayHeight = Math.ceil( pr_height * 0.4 );
			css_wh = 'width:100%; height:' + self.DisplayHeight + 'px;';
			pr_container_parent.style.cssText = 'position:relative; height:' + self.DisplayHeight + 'px;';
		}
		self.container_css = 'position:absolute; top:0px; cursor:default; background:#000000; overflow:auto; ' + css_wh;
		pr_container.style.cssText = self.container_css;
	}
	prZoom( 0 );
}

function prSetup() {
	self.pr_horiz = mw.user.options.get( 'proofreadpage-horizontal-layout' );
	if ( !self.pr_horiz ) {
		// This is kept for compatibility reasons - it will be removed in the future
		self.pr_horiz = ( self.proofreadpage_default_layout == 'horizontal' );
	}
	if( !proofreadPageIsEdit ) {
		pr_horiz = false;
	}

	self.table = document.createElement( 'div' );
	self.text_container = document.createElement( 'div' );

	pr_container = document.createElement( 'div' );
	pr_container.setAttribute( 'id', 'pr_container' );

	self.pr_container_parent = document.createElement( 'div' );
	pr_container_parent.appendChild( pr_container );

	// Get the size of the window
	if( typeof( window.innerWidth ) == 'number' ) {
		// Non-IE
		pr_width = window.innerWidth;
		pr_height = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		// IE 6+ in 'standards compliant mode'
		pr_width = document.documentElement.clientWidth;
		pr_height = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		// IE 4 compatible
		pr_width = document.body.clientWidth;
		pr_height = document.body.clientHeight;
	}

	// fill the image container
	if( !proofreadPageIsEdit ) {
		prFetchThumbUrl( parseInt( pr_width / 2 - 70 ), function( url, width, height ) {
			var image = document.createElement( 'img' );
			image.setAttribute( 'id', 'ProofReadImage' );
			image.setAttribute( 'src', url );
			image.setAttribute( 'width', width );
			image.style.cssText = 'padding:0;margin:0;border:0;';
			pr_container.appendChild( image );
			pr_container.style.cssText = 'overflow:hidden;width:' + width + 'px;';
			prInitZoom( width, height );
		} );
	} else {
		var w = parseInt( self.proofreadPageEditWidth );
		if( !w ) {
			w = self.proofreadPageDefaultEditWidth;
		}
		if( !w ) {
			w = 1024; /* Default size in edit mode */
		}

		// prevent the container from being resized once the image is downloaded.
		img_width = pr_horiz ? 0 : parseInt( pr_width / 2 - 70 ) - 20;
		pr_container.onmousedown = prGrab;
		pr_container.onmousemove = prMove;
		if ( pr_container.addEventListener ) {
			pr_container.addEventListener( 'DOMMouseScroll', prZoomWheel, false );
		}
		pr_container.onmousewheel = prZoomWheel; // IE, Opera.

		prFetchThumbUrl( Math.min( w, self.proofreadPageWidth ), function( url, width, height ) {
			pr_container.innerHTML = '<img id="ProofReadImage" src="' +
				mw.html.escape( url ) + '" width="' + img_width + '" />';
			prZoom( 0 );
		} );
	}

	table.setAttribute( 'id', 'textBoxTable' );
	table.style.cssText = 'width:100%;';

	prFillTable();

	// insert the image
	var text;

	if( proofreadPageIsEdit ) {
		text = document.getElementById( 'wpTextbox1' );
	} else {
		text = document.getElementById( 'bodyContent' );
	}

	if( !text ) {
		return;
	}
	var textParent = text.parentNode;
	var textSibling = text.nextSibling;
	text = textParent.removeChild( text );

	if( proofreadPageIsEdit ) {
		prMakeEditArea( self.text_container, text );
		textParent.insertBefore( table, textSibling ); // Inserts table after text
		if ( mw.user.options.get( 'proofreadpage-showheaders' ) ) {
			prResetSize();
		} else {
			prToggleVisibility();
		}

	} else {
		self.text_container.appendChild( text );
		textParent.insertBefore( table, textSibling );
	}

	// add buttons
	if( proofreadPageIsEdit ) {
		var tools = {
			'section': 'proofreadpage-tools',
			'groups': {
				'zoom': {
					'label': mw.msg( 'proofreadpage-group-zoom' ),
					'tools': {
						'zoom-in': {
							label: mw.msg( 'proofreadpage-button-zoom-in-label' ),
							type: 'button',
							icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_zoom_in.png',
							action: {
								type: 'callback',
								execute: function() {
									xx=0;
									yy=0;
									prZoom(2);
								}
							}
						},
						'zoom-out': {
							label: mw.msg( 'proofreadpage-button-zoom-out-label' ),
							type: 'button',
							icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_zoom_out.png',
							action: {
								type: 'callback',
								execute: function() {
									xx=0;
									yy=0;
									prZoom(-2);
								}
							}
						},
						'reset-zoom': {
							label: mw.msg( 'proofreadpage-button-reset-zoom-label' ),
							type: 'button',
							icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_examine.png',
							action: {
								type: 'callback',
								execute: function() {
									prZoom(0);
								}
							}
						}
					}
				},
				'other': {
					'label': mw.msg( 'proofreadpage-group-other' ),
					'tools': {
						'toggle-visibility': {
							label: mw.msg( 'proofreadpage-button-toggle-visibility-label' ),
							type: 'button',
							icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_category_plus.png',
							action: {
								type: 'callback',
								execute: function() {
									prToggleVisibility();
								}
							}
						},
						'toggle-layout': {
							label: mw.msg( 'proofreadpage-button-toggle-layout-label' ),
							type: 'button',
							icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_multicol.png',
							action: {
								type: 'callback',
								execute: function() {
									prToggleLayout();
								}
							}
						}
					}
				}
			}
		};

		var $edit = $( '#wpTextbox1' );
		if( mw.user.options.get('usebetatoolbar') && typeof $edit.wikiEditor === 'function' ) {
			$edit.wikiEditor( 'addToToolbar', {
				'sections': {
					'proofreadpage-tools': {
						'type': 'toolbar',
						'label': mw.msg( 'proofreadpage-section-tools' )
					}
				}
			} )
			.wikiEditor( 'addToToolbar', tools);
		} else {
			var toolbar = document.getElementById( 'toolbar' );

			pr_rect = document.createElement( 'div' );
			pr_container_parent.appendChild( pr_rect );

			if( ( !toolbar ) || ( self.wgWikiEditorPreferences && self.wgWikiEditorPreferences['toolbar'] ) ) {
				toolbar = document.createElement( 'div' );
				toolbar.style.cssText = 'position:absolute;';
				pr_container_parent.appendChild( toolbar );
			}

			var bits = [
				tools.groups.other.tools['toggle-visibility'],
				tools.groups.zoom.tools['zoom-out'],
				tools.groups.zoom.tools['reset-zoom'],
				tools.groups.zoom.tools['zoom-in'],
				tools.groups.other.tools['toggle-layout']
			];
			$.each(bits, function(i, button) {
				var image = document.createElement( 'img' );
				image.style.cssText = 'width: 23px; height: 23px; ';
				image.className = 'mw-toolbar-editbutton';
				image.src = button.icon;
				image.border = 0;
				image.alt = button.label;
				image.title = button.label;
				image.onclick = button.action.execute;
				toolbar.appendChild( image );
			});
		}
	}
}

function prInit() {
	if( document.getElementById( 'pr_container' ) ) {
		return;
	}

	if( $.inArray( mw.config.get( 'wgAction' ), ['protect', 'unprotect', 'delete', 'undelete', 'watch', 'unwatch', 'history'] ) !== -1 ) {
		return;
	}

	if( mw.config.get( 'proofreadPageFileName' ) == null ) {
		// File does not exist
		return;
	}

	if( self.proofreadpage_setup ) {
		// Run custom site/user setup code
		proofreadpage_setup(
			proofreadPageWidth,
			proofreadPageHeight,
			proofreadPageIsEdit
		);
	} else {
		// Run extension setup code
		prSetup();
	}

	// add CSS classes to the container div
	if( self.proofreadPageCss) {
		$( 'div.pagetext' ).addClass( self.proofreadPageCss );
	}
}

function prAddQualityButtons() {
	if ( !mw.config.get( 'proofreadPageIsEdit' ) ) {
		return;
	}

	var lastProofreader = proofreadpage_username || '';
	var currentQualityLevel = proofreadpage_quality;

	var $container = jQuery( '<span/>' );
	$container.append( jQuery( '<input type="hidden" name="wpProofreader" />' ).val( lastProofreader ) );

	jQuery( '.editCheckboxes' ).append( $container );

	if ( !mw.config.get( 'proofreadPageAddButtons' ) ) {
		// User has no premissions to change the quality level
		$container.append( jQuery( '<input type="hidden" name="wpQuality" />' ).val( currentQualityLevel ) );
		return;
	}

	var qualityLevels = [ 0, 2, 1, 3, 4 ];
	$radioList = jQuery( '<span id="wpQuality-container" />' );

	for ( var i = 0; i < qualityLevels.length; i++ ) {
		var level = qualityLevels[i];

		var $input = jQuery( '<input type="radio" name="wpQuality" tabindex="4" />' );
		$input
			.val( level )
			.attr( 'checked', level === currentQualityLevel )
			.attr( 'title', mw.msg( 'proofreadpage_quality' + level + '_category' ) )
			.click( function() {
				var text = mw.msg( 'proofreadpage_quality' + this.value + '_category' );
				this.form.elements['wpSummary'].value = '/* ' + text + ' */ ';
				this.form.elements['wpProofreader'].value = mw.config.get( 'proofreadPageUserName' );
			} );

		var $span = jQuery( '<span class="quality' + level + '" />' );
		$span.append( $input );

		$radioList.append( $span );
	}
	$container
		.append( $radioList )
		.append( '&nbsp;<label for="wpQuality-container">' + prParseLink( mw.html.escape( mw.msg( 'proofreadpage_page_status' ) ) ) + '</label>'); //no "for" property: it's a label for the 4 radio buttons.

	if ( currentQualityLevel !== 4
		&& ( currentQualityLevel !== 3 || lastProofreader === mw.config.get( 'proofreadPageUserName' ) )
	) {
		document.editform.wpQuality[4].parentNode.style.cssText = 'display:none';
		document.editform.wpQuality[4].disabled = true;
	}
}

jQuery( prInitTabs );

function prStartup() {
	jQuery( function() {
//		prInit();
		prInitZoom();
		prSetSummary();
		prHideHeaderFooter();
	} );
}

if ( mw.user.options.get( 'usebetatoolbar' ) && jQuery.inArray( 'ext.wikiEditor.toolbar', mw.loader.getModuleNames() ) > -1 ) {
	mw.loader.using( 'ext.wikiEditor.toolbar', function() {
		// Load the whole thing after the toolbar has been constructed
		prStartup();
	} );
} else {
	prStartup();
}

<!--
/**
 * plxEditor
 *
 * @package PLX
 * @version 1.0 beta 1
 * @date	01/07/2011
 * @author	Stephane F
 **/
PLUXML={};

function E$(id){return document.getElementById(id)}

PLUXML.editor=function() {

	var buttons = ['bold', 'italic', 'underline', 'strikethrough', 'separator', 'image', 'forecolor', 'backcolor', 'link', 'unlink', 'removeformat', 'separator', 'justifyleft', 'justifycenter', 'justifyright', 'separator', 'insertorderedlist', 'insertunorderedlist', 'separator', 'outdent', 'indent', 'separator', 'subscript', 'superscript', 'smilies', 'separator', 'html', 'fullscreen'];

	function create(editorName, textareaId, images_path, documents_path){
		this.patheditor = '../../plugins/plxeditor/';
		this.pathimg=this.patheditor+'plxeditor/images/';
		this.pathcss=this.patheditor+'plxeditor/css/';
		this.editor = editorName;
		this.textareaId = textareaId;
		// Chargement des données avec conversion des liens
		this.images_path=images_path;
		this.documents_path=documents_path;
		this.textareaValue = this.convertLinks(E$(this.textareaId).value, 0);
		//
		this.sel = null;
		this.popup = null;
		this.viewSource = false;
		this.viewFullscreen = false;
		this.browser = {
			"ie": Boolean(document.body.currentStyle),
			"gecko" : (navigator.userAgent.toLowerCase().indexOf("gecko") != -1)
		}
		// EDITOR
		var editor = document.createElement("div");
		editor.id = this.textareaId+"-wysiwyg";
		editor.setAttribute('class', 'wysiwyg');
		editor.innerHTML = this.getEditorHtml();
		E$(this.textareaId).parentNode.replaceChild(editor, E$(this.textareaId));
		// BUTTONS
		var buttons = editor.getElementsByTagName("td");
		for (var i = 0; i < buttons.length; ++i) {
			if (buttons[i].className == "button") {
				buttons[i].id = this.textareaId+'-button-'+i;
				buttons[i].onmouseover = function() { this.className = "button-hover"; }
				buttons[i].onmouseout = function() { this.className = this.className.replace(/button-hover(\s)?/, "button"); }
			}
		}
		// FRAME
		this.frame = E$(this.textareaId+"-iframe").contentWindow;
		this.frame.document.designMode = "on";
		this.frame.document.open();
		this.frame.document.write(this.getFrameHtml());
		this.frame.document.close();
		this.setFrameContent();
		// Update the textarea with content in iframe when user submits form
		var f_submit = this.editor + '.updateTextArea()';
		for (var i=0;i<document.forms.length;i++) { PLUXML.event.addEvent(document.forms[i], 'submit', function() { eval(f_submit) }); }
		//var f_keypress = this.editor + '.keypress(evt)';
		//PLUXML.event.addEvent(this.frame, 'keypress', function(evt) { eval(f_keypress) });
	}
	//------------
	/*
    create.prototype.keypress=function(evt) {
		if (!this.viewSource && evt.keyCode==13) {
	    	if (evt.shiftKey) {
			}
			evt.cancelBubble = true;
			if (evt.stopPropagation) { evt.stopPropagation(); }
			evt.returnValue = false;
			if (evt.preventDefault) {  evt.preventDefault(); }
			return false;
		}
	},
	*/
	create.prototype.getEditorHtml=function() {
        var html = '';
		html += '<input type="hidden" id="'+this.textareaId+'" name="'+this.textareaId.replace(/id_/,'')+'" value="" />';
		html += '<div id="'+this.textareaId+'-toolbars" class="toolbars">';
		// toolbar
		html += '<table id="'+this.textareaId+'-toolbar" class="buttons" cellspacing="0" cellpadding="0"><tr>';
		html += '<td>&nbsp;<select onchange="'+this.editor+'.execCommand(\'formatblock\', this.value);this.selectedIndex=0;"><option value="">Style</option><option value="<h1>">H1</option><option value="<h2>">H2</option><option value="<h3>">H3</option><option value="<p>">P</option><option value="<pre>">Pre</option></select></td>';
		html += '<td><div class="separator"></div></td>';
		for (var i = 0; i < buttons.length; ++i) {
			if(buttons[i]=='separator') {
				html += '<td><div class="separator"></div></td>';
			} else {
				html += '<td class="button"><img id="'+this.textareaId+'-'+buttons[i]+'" src="'+this.pathimg+buttons[i]+'.gif" width="20" height="20" alt="" title="" onclick="'+this.editor+'.execCommand(\''+buttons[i]+'\')" /></td>';
			}
		}
		html += '</tr></table>';
		html += '</div>';
		// iframe
		html += '<div id="'+this.textareaId+'-editor" class="editor">';
		html += '<iframe id="'+this.textareaId+'-iframe" class="iframe" frameborder="0"></iframe>';
		html += '</div>'; // fin frame
		return html;
    },
/*
	create.prototype.getRange=function(sel) {
		return sel.rangeCount > 0 ? sel.getRangeAt(0) : (sel.createRange ? sel.createRange() : null);
	},
*/
	create.prototype.trim=function(str) {
		try {return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '') } catch(e) { return str; };
	},
    create.prototype.execCommand=function(cmd, value) {
		if (cmd == "image" && !value) {
			this.frame.focus();
			var win = this.frame; var doc = win.document;
			if (this.browser.ie) {
				s = doc.selection.createRange().text;
				this.sel = doc.selection.createRange();
			}
			this.openPopup(this.patheditor+'medias.php?id='+this.editor, this.editor, 780, 580);
		} else if (cmd == "link" && !value) {
			this.frame.focus();
			var win = this.frame; var doc = win.document;
			if (this.browser.ie) {
				s = doc.selection.createRange().text;
				this.sel = doc.selection.createRange();
			} else  {
				s = win.getSelection().getRangeAt(0).toString();
			}
			new PLUXML.linker.create(this.editor, this.textareaId+'-link', this.trim(s));
		} else if (cmd == "forecolor" && !value) {
			new PLUXML.cpicker.create(this.editor, this.textareaId+'-forecolor', "forecolor");
		} else if (cmd == "backcolor" && !value) {
			new PLUXML.cpicker.create(this.editor, this.textareaId+'-backcolor', (this.browser.ie ? "backcolor" : "hilitecolor") );
		} else if (cmd == "smilies" && !value) {
			new PLUXML.smilies.create(this.editor, this.textareaId+'-smilies', "smilies");
		} else if (cmd == "html" && !value) {
			this.toggleSource();
		} else if (cmd == "fullscreen" && !value) {
			this.toggleFullscreen();
		} else if (cmd == "inserthtml" && this.browser.ie) { // IE
			if(this.viewSource==true) return;
			this.frame.focus();
			this.sel.pasteHTML(value);
			this.sel.select();
			this.frame.focus();
		} else {
			if(this.viewSource==true) return;
			this.frame.focus();
			this.frame.document.execCommand(cmd, false, value);
			this.frame.focus();
		}
	},
    create.prototype.updateTextArea=function() {
		if(this.viewSource) { this.toggleSource(); }
		txt = this.frame.document.body.innerHTML;
		txt = this.convertLinks(txt, 1); // conversion des liens
		txt = this.toXHTML(txt);
		E$(this.textareaId).value = txt;
	},
    create.prototype.setFrameContent=function () {
		try { this.frame.document.body.innerHTML = this.textareaValue; } catch (e) { setTimeout(this.setFrameContent, 10); }
    },
    create.prototype.getFrameHtml=function() {
        var html = "";
		html += '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        html += '<html><head>';
        html += '<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">';
        html += '<style type="text/css">pre { background-color: #fff; padding: 0.75em 1.5em; border: 1px solid #dddddd; }</style>';
        html += '<style type="text/css">html,body { cursor: text; } body { margin: 0.5em; padding: 0; } img { border:none; }</style>';
        html += '</head><body></body></html>';
        return html;
    },
    create.prototype.toggleFullscreen = function() {
		var f = this.editor+".resize()";
		if (!this.viewFullscreen) {
			E$(this.textareaId+'-wysiwyg').setAttribute('class', 'wysiwyg-fullscreen');
			E$(this.textareaId+'-toolbars').setAttribute('class', 'toolbars-fullscreen');
			E$(this.textareaId+'-editor').setAttribute('class', 'editor-fullscreen');
			E$(this.textareaId+'-iframe').setAttribute('class', 'iframe-fullscreen');
			E$(this.textareaId+'-wysiwyg').setAttribute('style', 'height:'+this.getViewportHeight()+'px');
			PLUXML.event.addEvent(window, 'resize', function() { eval(f) } );
			this.viewFullscreen = true;
		} else {
			E$(this.textareaId+'-wysiwyg').setAttribute('class', 'wysiwyg');
			E$(this.textareaId+'-toolbars').setAttribute('class', 'toolbars');
			E$(this.textareaId+'-editor').setAttribute('class', 'editor');
			E$(this.textareaId+'-iframe').setAttribute('class', 'iframe');
			E$(this.textareaId+'-wysiwyg').setAttribute('style', 'height:300px');
			PLUXML.event.removeEvent(window, 'resize', function() { eval(f) } );
			this.viewFullscreen = false;
		}
		this.frame.focus();
	},
	create.prototype.resize=function() {
		E$(this.textareaId+'-wysiwyg').setAttribute('style', 'height:'+this.getViewportHeight()+'px');
	},
	create.prototype.getViewportHeight=function() {
		var height;
		if (window.innerHeight!=window.undefined) height=window.innerHeight;
		else if (document.compatMode=='CSS1Compat') height=document.documentElement.clientHeight;
		else if (document.body) height=document.body.clientHeight;
		return height-100;
	},
	create.prototype.convertLinks=function(txt, how) {
		// conversion des liens
		if(how==0) {
			txt=txt.replace(new RegExp(this.images_path, 'g'), "../../"+this.images_path);
			txt=txt.replace(new RegExp(this.documents_path, 'g'), "../../"+this.documents_path);
		} else {
			txt=txt.replace(new RegExp("../../"+this.images_path, 'g'), this.images_path);
			txt=txt.replace(new RegExp("../../"+this.documents_path, 'g'), this.documents_path);
		}
		return txt;
	},
    create.prototype.toggleSource=function() {
        var html, txt;
		if (!this.viewSource) {
			txt = this.frame.document.body.innerHTML;
			// conversion des liens
			txt = this.convertLinks(txt, 1);
			txt = this.toXHTML(txt);
			txt = this.formatHTML(txt);
			this.frame.document.body.innerHTML = txt.toString();
			// change icon image
			E$(this.textareaId+'-html').src = this.pathimg+'text.gif';
			// set color css file
			var filecss=this.frame.document.createElement("link");
			filecss.rel = 'stylesheet'
			filecss.type = 'text/css';
			filecss.href = this.pathcss+'viewsource.css';
			this.frame.document.getElementsByTagName("head")[0].appendChild(filecss);
			// set the font values for displaying HTML source
			this.frame.document.body.style.fontSize = "13px";
			this.frame.document.body.style.fontFamily = "Courier New";
			this.viewSource = true;
		} else {
			if (this.browser.ie) {
                txt = this.frame.document.body.innerText;
				// conversion des liens
				txt = this.convertLinks(txt.toString(), 0);
                this.frame.document.body.innerHTML = txt;
			} else {
				html = this.frame.document.body.ownerDocument.createRange();
				html.selectNodeContents(this.frame.document.body);
				// conversion des liens
				txt = this.convertLinks(html.toString(), 0);
				this.frame.document.body.innerHTML = txt;
			}
			// change icon image
			E$(this.textareaId+'-html').src = this.pathimg+'html.gif';
			// set the font values for displaying HTML source
			this.frame.document.body.style.fontSize = "";
			this.frame.document.body.style.fontFamily = "";
			this.viewSource = false;
		}
    },
	create.prototype.toXHTML=function(v) {
		function lc(str){return str.toLowerCase()}
		function sa(str){return str.replace(/("|;)\s*[A-Z-]+\s*:/g,lc);}
		v=v.replace(/rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\)/gi, function toHex($1,$2,$3,$4) { return '#' + (1 << 24 | $2 << 16 | $3 << 8 | $4).toString(16).substr(1); });
		v=v.replace(/<span class="apple-style-span">(.*)<\/span>/gi,'$1');
		v=v.replace(/s*class="apple-style-span"/gi,'');
		v=v.replace(/s*class="webkit-indent-blockquote"/gi,'');
		v=v.replace(/<span style="">/gi,'');
		v=v.replace(/<b\b[^>]*>(.*?)<\/b[^>]*>/gi,'<strong>$1</strong>');
		v=v.replace(/<i\b[^>]*>(.*?)<\/i[^>]*>/gi,'<em>$1</em>');
		v=v.replace(/<(s|strike)\b[^>]*>(.*?)<\/(s|strike)[^>]*>/gi,'<span style="text-decoration: line-through;">$2</span>');
		v=v.replace(/<u\b[^>]*>(.*?)<\/u[^>]*>/gi,'<span style="text-decoration:underline">$1</span>');
		v=v.replace(/<(b|strong|em|i|u) style="font-weight: normal;?">(.*)<\/(b|strong|em|i|u)>/gi,'$2');
		v=v.replace(/<(b|strong|em|i|u) style="(.*)">(.*)<\/(b|strong|em|i|u)>/gi,'<span style="$2"><$4>$3</$4></span>');
		v=v.replace(/<blockquote .*?>(.*?)<\/blockquote>/gi,'<blockquote>$1<\/blockquote>');
		v=v.replace(/<span style="font-weight: normal;?">(.*?)<\/span>/gi,'$1');
		v=v.replace(/<span style="font-weight: bold;?">(.*?)<\/span>/gi,'<strong>$1</strong>');
		v=v.replace(/<span style="font-style: italic;?">(.*?)<\/span>/gi,'<em>$1</em>');
		v=v.replace(/<span style="font-weight: bold;?">(.*?)<\/span>|<b\b[^>]*>(.*?)<\/b[^>]*>/gi,'<strong>$1</strong>')
		v=v.replace(/BACKGROUND-COLOR/gi,'background-color');
		//v=v.replace(/<div><br \/><\/div>/gi, '<p></p>');
		v=v.replace(/<(IMG|INPUT|BR|HR|LINK|META)([^>]*)>/gi,"<$1$2 />") //self-close tags
		v=v.replace(/(<\/?[A-Z]*)/g,lc) // lowercase tags
		v=v.replace(/STYLE="[^"]*"/gi,sa); //lc style atts
		return v;
	},
	create.prototype.formatHTML=function(html) {
		//strip white space
		html = html.replace(/\s/g, ' ');
		//convert html to text
		html = html.replace(/&/g, '&amp;');
		html = html.replace(/</g, '&lt;');
		html = html.replace(/>/g, '&gt;');
		//change all attributes " to &quot; so they can be distinguished from the html we are adding
		html = html.replace(/="/g, '=&quot;');
		html = html.replace(/=&quot;(.*?)"/g, '=&quot;$1&quot;');
		//search for opening tags
		html = html.replace(/&lt;([a-z](?:[^&|^<]+|&(?!gt;))*?)&gt;/gi, "<span class=\"tag\">&lt;$1&gt;</span><blockquote>");
		//Search for closing tags
		html = html.replace(/&lt;\/([a-z].*?)&gt;/gi, "</blockquote><span class=\"tag\">&lt;/$1&gt;</span>");
		//search for self closing tags
		html = html.replace(/\/&gt;<\/span><blockquote>/gi, "/&gt;</span>");
		//Search for values
		html = html.replace(/&quot;(.*?)&quot;/gi, "<span class=\"literal\">\"$1\"</span>");
		//search for comments
		html = html.replace(/&lt;!--(.*?)--&gt;/gi, "<span class=\"comment\">&lt;!--$1--&gt;</span>");
		//search for html entities
		html = html.replace(/&amp;(.*?);/g, '<b>&amp;$1;</b>');
		return html;
	},
	create.prototype.openPopup=function(fichier,nom,width,height) {
		this.popup = window.open(unescape(fichier) , nom, "directories=no, toolbar=no, menubar=no, location=no, resizable=yes, scrollbars=yes, width="+width+" , height="+height);
		if(this.popup) {
			this.popup.focus();
		} else {
			alert('Ouverture de la fenetre bloquee par un anti-popup!');
		}
		return;
	};
	return{create:create}
}();
PLUXML.event=function() {
	return {
		addEvent:function(obj, evType, fn){
			if (obj.addEventListener){
				obj.addEventListener(evType, fn, false);
				return true;
			} else if (obj.attachEvent){
				var r = obj.attachEvent("on"+evType, fn);
				return r;
			} else {
				return false;
			}
		},
		removeEvent:function removeEvent(obj, evType, fn, useCapture){
			if (obj.removeEventListener){
				obj.removeEventListener(evType, fn, useCapture);
				return true;
			} else if (obj.detachEvent){
				var r = obj.detachEvent("on"+evType, fn);
				return r;
			} else {
				alert("Handler could not be removed");
			}
		}
	}
}();
PLUXML.dialog=function() {
	return {
		close:function(obj){
			var dialog = E$(obj);
			if(dialog!=null) {
				document.body.removeChild(dialog); return;
			}
		},
		getAbsoluteOffsetTop:function(obj){
			var top = obj.offsetTop;
			var parent = obj.offsetParent;
			while (parent != document.body) {
					top += parent.offsetTop;
			parent = parent.offsetParent;
			}
			return top;
		},
		getAbsoluteOffsetLeft:function(obj) {
			var left = obj.offsetLeft;
			var parent = obj.offsetParent;
			while (parent != document.body) {
				left += parent.offsetLeft;
				parent = parent.offsetParent;
			}
			return left;
		}
	}
}();
PLUXML.linker=function() {
	function create(editor, button, value){
		this.editor=editor;
		this.button=button;
		this.value=value;
		if(E$('linker')) return PLUXML.dialog.close('linker');
		this.showPanel();
	}
	//------------
	create.prototype.showPanel=function(){
        var elemDiv = document.createElement('div');
        elemDiv.id = 'linker';
	    elemDiv.style.position = 'absolute';
        elemDiv.style.display = 'block';
        elemDiv.style.border = '#dedede 1px solid';
        elemDiv.style.background = '#f2f2f2';
		elemDiv.style.padding = '10px 5px 2px 5px';
		var top = PLUXML.dialog.getAbsoluteOffsetTop(E$(this.button)) + 20;
		var left = PLUXML.dialog.getAbsoluteOffsetLeft(E$(this.button));
     	elemDiv.style.top = top + 'px';
     	elemDiv.style.left = left + 'px';
        elemDiv.innerHTML = this.panel();
        document.body.appendChild(elemDiv);
	},
	create.prototype.panel=function() {
		var table = '<table border="0" cellspacing="0" cellpadding="0">';
		table += '<tr><td style="padding:0">Lien :</td><td style="padding:0"><input type="text" value="http://" id="txtHref" /></td></tr>';
		table += '<tr><td style="padding:0 0 5px 0">Titre du lien :</td><td style="padding:0 0 5px 0"><input type="text" value="'+this.value+'" id="txtTitle" /></td></tr>';
		table += '<tr><td style="border-top:#dedede 1px solid;padding:5px 0 0 0">class :</td><td style="border-top:#dedede 1px solid;padding:5px 0 0 0"><input type="text" value="" id="txtClass" /></td></tr>';
		table += '<tr"><td style="padding:0">rel :</td><td style="padding:0"><input type="text" value="" id="txtRel" /></td></tr>';
		table += '<tr><td colspan="2" style="text-align:center"><input type="submit" value="Ajouter" onclick="PLUXML.linker.setLink('+this.editor+')" />&nbsp;<input type="submit" name="btnCancel" id="btnCancel" value="Annuler" onclick="PLUXML.dialog.close(\'linker\')" /></td></tr>';
		table += '</table>';
		return table;
	};
	return{
		create:create,
		setLink:function(editor) {
			var sHref = (E$('txtHref') ? E$('txtHref').value : '');
			var sTtitle = (E$('txtTitle') ? E$('txtTitle').value : '');
			var sClass = (E$('txtClass') ? (E$('txtClass').value!=''? ' class="'+E$('txtClass').value+'"':'') : '');
			var sRel = (E$('txtRel') ? (E$('txtRel').value!=''? ' rel="'+E$('txtRel').value+'"':'') : '');
			if(sTtitle=='' || PLUXML.linker.isUrl(sHref)==false) return;
			editor.execCommand('inserthtml', '<a href="'+sHref+'" title="'+sTtitle+'"'+sClass+sRel+'>'+sTtitle+'</a> ');
			PLUXML.dialog.close('linker');
		},
		isUrl:function(s) {
			var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
			return regexp.test(s);
		}
	}
}();
PLUXML.cpicker=function(){

	var colors = [
		["ffffff", "ffcccc", "ffcc99", "ffff99", "ffffcc", "99ff99", "99ffff", "ccffff", "ccccff", "ffccff"],
		["cccccc", "ff6666", "ff9966", "ffff66", "ffff33", "66ff99", "33ffff", "66ffff", "9999ff", "ff99ff"],
		["c0c0c0", "ff0000", "ff9900", "ffcc66", "ffff00", "33ff33", "66cccc", "33ccff", "6666cc", "cc66cc"],
		["999999", "cc0000", "ff6600", "ffcc33", "ffcc00", "33cc00", "00cccc", "3366ff", "6633ff", "cc33cc"],
		["666666", "990000", "cc6600", "cc9933", "999900", "009900", "339999", "3333ff", "6600cc", "993399"],
		["333333", "660000", "993300", "996633", "666600", "006600", "336666", "000099", "333399", "663366"],
		["000000", "330000", "663300", "663333", "333300", "003300", "003333", "000066", "330099", "330033"]];

	function create(editor, button, action){
		this.editor=editor;
		this.button=button;
		this.action=action;
		if(E$('colorpicker')) return PLUXML.dialog.close('colorpicker');
		this.displayPanel();
	}
	//------------
	create.prototype.displayPanel=function(){
        var elemDiv = document.createElement('div');
        elemDiv.id = 'colorpicker';
	    elemDiv.style.position = 'absolute';
        elemDiv.style.display = 'block';
        elemDiv.style.border = '#000000 1px solid';
        elemDiv.style.background = '#ffffff';
		var top = PLUXML.dialog.getAbsoluteOffsetTop(E$(this.button)) + 20;
		var left = PLUXML.dialog.getAbsoluteOffsetLeft(E$(this.button));
     	elemDiv.style.top = top + 'px';
     	elemDiv.style.left = left + 'px';
        elemDiv.innerHTML = this.panel();
        document.body.appendChild(elemDiv);
	},
	create.prototype.panel=function() {
		var table = '<table border="0" cellspacing="1" cellpadding="0">';
		for(var y=0; y < colors.length; y++) {
			table += '<tr style="padding:0;margin:0;border:none">';
			for(var x=0; x < colors[y].length; x++) {
				table += '<td style="padding:0;margin:0"><a style="border:1px solid #000; color: #' + colors[y][x] + '; background-color: #' + colors[y][x] + ';font-size: 10px;" title="' + colors[y][x] + '" href="javascript:'+this.editor+'.execCommand(\''+this.action+'\', \'#' + colors[y][x] + '\');PLUXML.dialog.close(\'colorpicker\');">&nbsp;&nbsp;&nbsp;&nbsp;</a></td>';
			}
			table += '</tr>';
		}
		table += '</table>';
		return table;
	};
	return{create:create}
}();
PLUXML.smilies=function(){

	var smilies = [
			["big_smile.png", "cool.png", "hmm.png", "icon_arrow.gif", "icon_eek.gif", "icon_exclaim.gif"],
			["icon_question.gif", "icon_redface.gif", "icon_twisted.gif", "lol.png", "mad.png", "neutral.png"],
			["roll.png", "sad.png", "smile.png", "tongue.png", "wink.png", "yikes.png"]];

	function create(editor, button, action){
		this.editor=editor;
		this.button=button;
		this.action=action;
		if(E$('smilies')) return PLUXML.dialog.close('smilies');
		this.displayPanel();
	}
	//------------
	create.prototype.displayPanel=function(){
        var elemDiv = document.createElement('div');
        elemDiv.id = 'smilies';
	    elemDiv.style.position = 'absolute';
        elemDiv.style.display = 'block';
        elemDiv.style.border = '#000000 1px solid';
        elemDiv.style.background = '#ffffff';
		var top = PLUXML.dialog.getAbsoluteOffsetTop(E$(this.button)) + 20;
		var left = PLUXML.dialog.getAbsoluteOffsetLeft(E$(this.button));
     	elemDiv.style.top = top + 'px';
     	elemDiv.style.left = left + 'px';
        elemDiv.innerHTML = this.panel();
        document.body.appendChild(elemDiv);
	},
	create.prototype.panel=function() {
		var table = '<table border="1" cellspacing="1" cellpadding="0">';
		for(var y=0; y < smilies.length; y++) {
			table += '<tr style="padding:0;margin:0;border:none">';
			for(var x=0; x < smilies[y].length; x++) {
				table += '<td style="padding:2px;margin:0"><a href="javascript:'+this.editor+'.execCommand(\'InsertImage\', \'../../plugins/plxeditor/plxeditor/smilies/' + smilies[y][x] + '\');PLUXML.dialog.close(\'smilies\');"><img alt="" src="../../plugins/plxeditor/plxeditor/smilies/'+smilies[y][x]+'" /></a></td>';
			}
			table += '</tr>';
		}
		table += '</table>';
		return table;
	};
	return{create:create}
}();
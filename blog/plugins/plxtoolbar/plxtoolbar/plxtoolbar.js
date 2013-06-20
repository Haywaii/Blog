<!--
/**
 * plxtoolbar
 *
 * @package PLX
 * @version 1.2
 * @date	01/07/2011
 * @author	Stephane F
 **/
lang={};

function _plxToolbar() {

	this.customButtons = new Array();
	this.path_editor = '';
	this.height;
	this.addButton = function(customButton) {
		this.customButtons.push(customButton);
	}
	this.insert = function(textarea, tag_open, tag_close, qst, msg) {
		if((answer = (qst ? prompt(qst, msg) : '')) == null) return;
		switch (tag_open) {
			case "<a>":
				tag_open = '<a href="'+answer+'">';
				break;
			case "<left>":
				tag_open = '\n<p style="text-align:left">';
				tag_close = '</p>';
				break;
			case "<center>":
				tag_open = '\n<p style="text-align:center">';
				tag_close = '</p>';
				break;
			case "<right>":
				tag_open = '\n<p style="text-align:right">';
				tag_close = '</p>';
				break;
		}
		this.addText(textarea, tag_open, tag_close);
	}
	this.doToolbar = function(textarea, origine, mini) {
		var url = window.location.pathname;
		var toolbar = '';
		if(mini=='mini') {
			toolbar += '<input class="bold" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<strong>\',\'<\/strong>\')" title="'+lang.L_TOOLBAR_BOLD+'" \/>';
			toolbar += '<input class="link" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<a>\',\'<\/a>\',\''+lang.L_TOOLBAR_LINK_MSG+'\', \'http://www.\')" title="'+lang.L_TOOLBAR_LINK+'" \/>';
		} else {
			toolbar += '<input class="p" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'\\n<p>\',\'<\/p>\')" title="'+lang.L_TOOLBAR_PARAGRAPH+'" \/>';
			toolbar += '<input class="h2" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<h2>\',\'<\/h2>\')" title="'+lang.L_TOOLBAR_TITLE+' H2" \/>';
			toolbar += '<input class="h3" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<h3>\',\'<\/h3>\')" title="'+lang.L_TOOLBAR_TITLE+' H3" \/>';
			toolbar += '<input class="h4" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<h4>\',\'<\/h4>\')" title="'+lang.L_TOOLBAR_TITLE+' H4" \/>';
			toolbar += '<input class="h5" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<h5>\',\'<\/h5>\')" title="'+lang.L_TOOLBAR_TITLE+' H5" \/>';
			toolbar += '<input class="bold" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<strong>\',\'<\/strong>\')" title="'+lang.L_TOOLBAR_BOLD+'" \/>';
			toolbar += '<input class="italic" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<em>\',\'<\/em>\')" title="'+lang.L_TOOLBAR_ITALIC+'" \/>';
			toolbar += '<input class="underline" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<ins>\',\'<\/ins>\')" title="'+lang.L_TOOLBAR_UNDERLINE+'" \/>';
			toolbar += '<input class="strike" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<del>\',\'\<\/del>\')" title="'+lang.L_TOOLBAR_STRIKE+'" \/>';
			toolbar += '<input class="link" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<a>\',\'<\/a>\',\''+lang.L_TOOLBAR_LINK_MSG+'\', \'http://www.\')" title="'+lang.L_TOOLBAR_LINK+'" \/>';
			toolbar += '<input class="br" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<br />\\n\',\'\')" title="'+lang.L_TOOLBAR_BR+'" \/>';
			toolbar += '<input class="hr" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<hr />\\n\',\'\')" title="'+lang.L_TOOLBAR_HR+'" \/>';
			toolbar += '<input class="ul" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'\\n<ul>\\n<li>\',\'<\/li>\\n<\/ul>\')" title="'+lang.L_TOOLBAR_UL+'" \/>';
			toolbar += '<input class="ol" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'\\n<ol>\\n<li>\',\'<\/li>\\n<\/ol>\')" title="'+lang.L_TOOLBAR_OL+'" \/>';
			toolbar += '<input class="blockquote" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'\\n<blockquote>\',\'<\/blockquote>\')" title="'+lang.L_TOOLBAR_BLOCKQUOTE+'" \/>';
			toolbar += '<input class="p_left" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<left>\',\'\')" title="'+lang.L_TOOLBAR_P_LEFT+'" \/>';
			toolbar += '<input class="p_center" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<center>\',\'\')" title="'+lang.L_TOOLBAR_P_CENTER+'" \/>';
			toolbar += '<input class="p_right" type="button" onclick="plxToolbar.insert(\''+textarea+'\',\'<right>\',\'\')" title="'+lang.L_TOOLBAR_P_RIGHT+'" \/>';
			toolbar += '<input class="media" type="button" onclick="plxToolbar.openPopup(\''+this.path_editor+'medias.php?id='+textarea+'\',\''+lang.L_TOOLBAR_MEDIAS+'\',\'750\',\'580\');return false;" title="'+lang.L_TOOLBAR_MEDIAS_TITLE+'" \/>';
			toolbar += '<input class="fullscreen" type="button" onclick="plxToolbar.toogleFullscreen(\''+textarea+'\')" title="'+lang.L_TOOLBAR_FULLSCREEN+'" \/>';
			if(this.customButtons.length>0) {
				toolbar += '<input class="separator" type="button" \/>';
				for(i=0;i<this.customButtons.length;i++){
					toolbar += '<input style="background:url('+this.customButtons[i].icon+') no-repeat;" class="button" type="button" onclick="plxToolbar.insert(\''+textarea+'\', plxToolbar.customButtons['+i+'].onclick(\''+textarea+'\'),\'\')" title="'+this.customButtons[i].title+'" \/>';
				}
			}
		}
		return toolbar;
	}
	this.addToolbar = function(textarea, origine, mini) {
		var obj = document.getElementById('id_'+textarea);
		var p = document.createElement('p');
		p.id = 'plxtoolbar_'+textarea;
		p.setAttribute("class","plxtoolbar");
		p.setAttribute("className","plxtoolbar"); /* Hack IE */
		p.innerHTML = this.doToolbar(textarea, origine, mini);
		var html = obj.parentNode;
		html.insertBefore(p,obj);

	}
	this.init = function(path_editor) {
		this.path_editor=path_editor;
		var url = window.location.pathname;
		var mini = '';
		if(url.match(new RegExp("comment.php","gi")))
			mini='mini';
		var textareas = document.getElementsByTagName("textarea");
		for(var i=0;i<textareas.length;i++){
			this.addToolbar(textareas[i].name,'article',mini);
		}
	}
	this.openPopup = function(fichier,nom,width,height) {
		popup = window.open(unescape(fichier) , nom, "directories=no, toolbar=no, menubar=no, location=no, resizable=yes, scrollbars=yes, width="+width+" , height="+height);
		if(popup) {
			popup.focus();
		} else {
			alert('Ouverture de la fenêtre bloquée par un anti-popup!');
		}
		return;
	}
	this.addText = function(where, open, close) {
		close = close==undefined ? '' : close;
		var formfield = document.getElementsByName(where)['0'];
		// IE support
		if (document.selection && document.selection.createRange) {
			formfield.focus();
			sel = document.selection.createRange();
			sel.text = open + sel.text + close;
			formfield.focus();
		}
		// Moz support
		else if (formfield.selectionStart || formfield.selectionStart == '0') {
			var startPos = formfield.selectionStart;
			var endPos = formfield.selectionEnd;
			var restoreTop = formfield.scrollTop;
			formfield.value = formfield.value.substring(0, startPos) + open + formfield.value.substring(startPos, endPos) + close + formfield.value.substring(endPos, formfield.value.length);
			formfield.selectionStart = formfield.selectionEnd = endPos + open.length + close.length;
			if (restoreTop > 0) formfield.scrollTop = restoreTop;
			formfield.focus();
		}
		// Fallback support for other browsers
		else {
			formfield.value += open + close;
			formfield.focus();
		}
		return;
	}
	this.insImg = function(where, src) {
		if(src.substr(-3)=='.tb')
			this.addText(where, '<a href="'+src.substr(0,src.length-3)+'"><img src="'+src+'" alt="" /></a>');
		else
			this.addText(where, '<img src="'+src+'" alt="" />');
	}
	this.insDoc = function(where, src, title, download) {
		if(download=='1')
			this.addText(where, '<a href="?download/'+src+'">'+title+'</a>');
		else
			this.addText(where, src);
	}
	this.toogleFullscreen = function(textarea) {
		var f = "plxToolbar.resize('"+textarea+"')";
		if(document.getElementById('id_'+textarea).getAttribute('class')!='textarea_fullscreen') {
			this.height=document.getElementById('id_'+textarea).offsetHeight;
			document.getElementById('p_'+textarea).setAttribute('class', 'p_fullscreen');
			document.getElementById('plxtoolbar_'+textarea).setAttribute('class', 'plxtoolbar plxtoolbar_fullscreen');
			document.getElementById('id_'+textarea).setAttribute('class', 'textarea_fullscreen');
			// Hacks IE
			document.getElementById('p_'+textarea).setAttribute('className', 'p_fullscreen');
			document.getElementById('plxtoolbar_'+textarea).setAttribute('className', 'plxtoolbar plxtoolbar_fullscreen');
			document.getElementById('id_'+textarea).setAttribute('className', 'textarea_fullscreen');
			//
			plxToolbar.resize(textarea);
			plxToolbar.addEvent(window, 'resize', function() { eval(f) } );
		} else {
			document.getElementById('p_'+textarea).removeAttribute('class');
			document.getElementById('plxtoolbar_'+textarea).setAttribute('class', 'plxtoolbar');
			document.getElementById('id_'+textarea).removeAttribute('class');
			document.getElementById('id_'+textarea).setAttribute('style', 'height:'+this.height+'px');
			// Hacks IE
			document.getElementById('p_'+textarea).removeAttribute('className');
			document.getElementById('plxtoolbar_'+textarea).setAttribute('className', 'plxtoolbar');
			document.getElementById('id_'+textarea).removeAttribute('className');
			document.getElementById('id_'+textarea).style.height = this.height+'px';
			plxToolbar.removeEvent(window, 'resize', function() { eval(f) } );
		}
	}
	this.resize=function(textarea) {
		document.getElementById('id_'+textarea).setAttribute('style', 'height:'+plxToolbar.getViewportHeight()+'px');
		// Hack IE
		document.getElementById('id_'+textarea).style.height = plxToolbar.getViewportHeight()+'px'
	},
	this.addEvent = function(obj, evType, fn){
		if (obj.addEventListener){
			obj.addEventListener(evType, fn, false);
			return true;
		} else if (obj.attachEvent){
			var r = obj.attachEvent("on"+evType, fn);
			return r;
		} else {
			return false;
		}
	}
	this.removeEvent = function(obj, evType, fn, useCapture){
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
	this.getViewportHeight = function() {
		var height;
		if (window.innerHeight!=window.undefined) height=window.innerHeight;
		else if (document.compatMode=='CSS1Compat') height=document.documentElement.clientHeight;
		else if (document.body) height=document.body.clientHeight;
		return height-150;
	}
}
var plxToolbar = new _plxToolbar();
-->

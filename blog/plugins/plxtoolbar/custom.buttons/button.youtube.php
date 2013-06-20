<?php
/**
 * button.youtube
 *
 * @package PLX 5.1
 * @author	Stéphane F
 * @version 1.01
 **/
?>
<?php if(!defined('PLX_ROOT')) exit; ?>

<script type="text/javascript">
<!--
function get_url_param(param,url) {
	param = param.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+param+"=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(url);
	if(results == null)
		return "";
	else
		return results[1];
}
plxToolbar.addButton( {
		icon : '<?php echo PLX_PLUGINS ?>plxtoolbar/custom.buttons/youtube.png',
		title : 'Vid&eacute;o Youtube',
		onclick : function() {
			var url = prompt('Url de la video youtube', 'http://www.youtube.com/watch?v=');
			if(url!=null) {
				var video = get_url_param('v', url);
				s  = '<object width="580" height="360">\n';
				s += '<param name="movie" value="http://www.youtube.com/v/'+video+'" \/>\n';
				s += '<param name="allowFullScreen" value="true" \/>\n';
				s += '<param name="allowscriptaccess" value="always" \/>\n';
				s += '<embed src="http://www.youtube.com/v/'+video+'" type="application\/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="580" height="360"><\/embed>\n';
				s += '<\/object>\n';
				return s;
			}
			return '';
		}
});
-->
</script>

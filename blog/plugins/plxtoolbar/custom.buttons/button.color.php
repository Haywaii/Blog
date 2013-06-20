<?php
/**
 * button.color
 *
 * @package PLX 5.1
 * @author	Stéphane F
 * @version 1.01
 **/
?>
<?php if(!defined('PLX_ROOT')) exit; ?>

<script type="text/javascript">
<!--
plxToolbar.addButton( {
		icon : '<?php echo PLX_PLUGINS ?>plxtoolbar/custom.buttons/color.png',
		title : 'Couleur',
		onclick : function(textarea) {
			var color = prompt('Code couleur (exemple: #ffffff)', '');
			if(color!=null) {
				plxToolbar.insert(textarea, '<span style="color:'+color+'">', '<\/span>', '', '');
			}
			return '';
		}
});
-->
</script>

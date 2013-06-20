<?php if(!defined('PLX_ROOT')) exit; ?>
<?php
if(!empty($_POST)) {
	$plxPlugin->setParam('style_mobile', $_POST['style_mobile'], 'string');
	$plxPlugin->saveParams();
	header('Location: parametres_plugin.php?p=plxmobile');
	exit;
}

# On récupère la liste des thèmes mobiles
$aStyles[''] = $plxPlugin->getLang('L_NONE');
$files = plxGlob::getInstance(PLX_ROOT.'themes', true);
if($styles = $files->query('/^mobile[a-z0-9-_\.\(\)]+/i')) {
	foreach($styles as $k=>$v)
		$aStyles[$v]=$v;
}
?>

<h2><?php echo $plxPlugin->getInfo('title') ?></h2>

<form action="parametres_plugin.php?p=plxmobile" method="post">
	<fieldset class="withlabel">
		<legend><?php $plxPlugin->lang('L_LEGEND') ?> :</legend>
		<p class="field"><label for="id_style_mobile"><?php $plxPlugin->lang('L_SELECT') ?>&nbsp;:</label></p>
		<?php plxUtils::printSelect('style_mobile', $aStyles, $plxPlugin->getParam('style_mobile')); ?>
		<p><input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>" /></p>
	</fieldset>
</form>
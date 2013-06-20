<?php if(!defined('PLX_ROOT')) exit; ?>
<?php

# Control du token du formulaire
plxToken::validateFormToken($_POST);

if(!empty($_POST)) {
	$plxPlugin->setParam('cc', $_POST['cc'], 'string');
	$plxPlugin->setParam('bcc', $_POST['bcc'], 'string');
	$plxPlugin->saveParams();
	header('Location: parametres_plugin.php?p=plxMyMailComment');
	exit;
}
$cc =  $plxPlugin->getParam('cc')=='' ? '' : $plxPlugin->getParam('cc');
$bcc =  $plxPlugin->getParam('bcc')=='' ? '' : $plxPlugin->getParam('bcc');

?>

<h2><?php echo $plxPlugin->getInfo('title') ?></h2>
<?php
if(function_exists('mail')) {
	echo '<p style="color:green"><strong>'.$plxPlugin->getLang('L_MAIL_AVAILABLE').'</strong></p>';
} else {
	echo '<p style="color:#ff0000"><strong>'.$plxPlugin->getLang('L_MAIL_NOT_AVAILABLE').'</strong></p>';
}
?>
<form id="form_plxMyMailComment" action="parametres_plugin.php?p=plxMyMailComment" method="post">
	<fieldset>
		<p class="field"><label for="id_cc"><?php $plxPlugin->lang('L_SENDER_CC') ?>&nbsp;:</label></p>
		<?php plxUtils::printInput('cc',$cc,'text','60-255') ?>
		<p class="field"><label for="id_bcc"><?php $plxPlugin->lang('L_SENDER_BCC') ?>&nbsp;:</label></p>
		<?php plxUtils::printInput('bcc',$bcc,'text','60-255') ?>
		<p>
			<?php echo plxToken::getTokenPostMethod() ?>
			<input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
		</p>
	</fieldset>
</form>

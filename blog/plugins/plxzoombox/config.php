

<?php if(!defined('PLX_ROOT')) exit; ?>
<?php
if(!empty($_POST)) {
	$plxPlugin->setParam('theme', $_POST['theme'], 'string');
	$plxPlugin->setParam('opacity', $_POST['opacity'], 'string');
	$plxPlugin->setParam('duration', $_POST['duration'], 'string');
	$plxPlugin->setParam('animation', $_POST['animation'], 'string');
	$plxPlugin->setParam('width', $_POST['width'], 'string');
	$plxPlugin->setParam('height', $_POST['height'], 'string');
	$plxPlugin->setParam('gallery', $_POST['gallery'], 'string');
	$plxPlugin->setParam('autoplay', $_POST['autoplay'], 'string');
	$plxPlugin->setParam('overflow', $_POST['overflow'], 'string');
	$plxPlugin->saveParams();

	header('Location: parametres_plugin.php?p=plxzoombox');
	exit;
}
?>

<h2><?php echo $plxPlugin->getInfo('title') ?></h2>

<form action="parametres_plugin.php?p=plxzoombox" method="post">
	<fieldset class="withlabel">
		
		<p><?php echo $plxPlugin->getLang('L_THEME') ?></p>
		<?php plxUtils::printSelect('theme',
			array(
				'zoombox'=>'zoombox',
				'lightbox'=>'lightbox', 
				'prettyphoto'=>'prettyphoto', 
				'darkprettyphoto'=>'darkprettyphoto', 
				'simple'=>'simple'), 
			$plxPlugin->getParam('theme'));
		?>
		
		<p><?php echo $plxPlugin->getLang('L_OPACITY') ?></p>
		<?php plxUtils::printSelect('opacity',
			array(
				'0'=>'0',
				'0.1'=>'0.1', 
				'0.2'=>'0.2', 
				'0.3'=>'0.3', 
				'0.4'=>'0.4', 
				'0.5'=>'0.5', 
				'0.6'=>'0.6', 
				'0.7'=>'0.7', 
				'0.8'=>'0.8', 
				'0.9'=>'0.9', 
				'1'=>'1'), 
			$plxPlugin->getParam('opacity')); 
		?>
		
		<p><?php echo $plxPlugin->getLang('L_DURATION') ?></p>
		<?php plxUtils::printInput('duration', $plxPlugin->getParam('duration'), 'text'); ?>
		
		<p><?php echo $plxPlugin->getLang('L_ANIMATION') ?></p>
		<?php plxUtils::printSelect('animation',
			array(
				'true'=>$plxPlugin->getLang('L_TRUE'),
				'false'=>$plxPlugin->getLang('L_FALSE')), 
			$plxPlugin->getParam('animation'));
		?>
		
		<p><?php echo $plxPlugin->getLang('L_WIDTH') ?></p>
		<?php plxUtils::printInput('width', $plxPlugin->getParam('width'), 'text'); ?>
		
		<p><?php echo $plxPlugin->getLang('L_HEIGHT') ?></p>
		<?php plxUtils::printInput('height', $plxPlugin->getParam('height'), 'text'); ?>

		<p><?php echo $plxPlugin->getLang('L_GALLERY') ?></p>
		<?php plxUtils::printSelect('gallery', 
			array(
				'true'=>$plxPlugin->getLang('L_TRUE'),
				'false'=>$plxPlugin->getLang('L_FALSE')),  
			$plxPlugin->getParam('gallery'));
		?>

		<p><?php echo $plxPlugin->getLang('L_AUTOPLAY') ?></p>
		<?php plxUtils::printSelect('autoplay',
			array(
				'true'=>$plxPlugin->getLang('L_TRUE'),
				'false'=>$plxPlugin->getLang('L_FALSE')), 
			$plxPlugin->getParam('autoplay')); 
		?>
		
		<p><?php echo $plxPlugin->getLang('L_OVERFLOW') ?></p>
		<?php plxUtils::printSelect('overflow',
			array(
				'true'=>$plxPlugin->getLang('L_TRUE'),
				'false'=>$plxPlugin->getLang('L_FALSE')), 
			$plxPlugin->getParam('overflow')); 
		?>

		<p><input type="submit" name="submit" value="Enregistrer" /></p>
</form>


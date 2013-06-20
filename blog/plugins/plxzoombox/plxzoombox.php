<?php
/**
 * Classe plxzoombox
 *
 * @package	plxzoombox
 * @date	06/02/2012
 * version	1.0
 * @author	Adaptation pour Zoombox du plugin plxyoxview de flipflip <flipflip@blogoflip.fr>, par Wiksa
 **/
class plxzoombox extends plxPlugin {

	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);
		
		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);

		# Ajouts des hooks
		$this->addHook('ThemeEndHead', 'plxHeaderZoomBox');

	}

	public function plxHeaderZoomBox () {
	
		$theme = $this->getParam('theme');
		$opacity = $this->getParam('opacity');
		$duration = $this->getParam('duration');
		$animation = $this->getParam('animation');
		$width = $this->getParam('width');
		$height = $this->getParam('height');
		$gallery = $this->getParam('gallery');
		$autoplay = $this->getParam('autoplay');
		$overflow = $this->getParam('overflow');
		
		echo "\t".'<script type="text/javascript" src="'.PLX_PLUGINS.'plxzoombox/zoombox/zoombox.js"></script>'."\n";
		echo "\t".'<link href="'.PLX_PLUGINS.'plxzoombox/zoombox/zoombox.css" rel="stylesheet" type="text/css" media="screen" />'."\n";
		
		echo '<script type="text/javascript">'."\n";
		echo "\t".'jQuery(function($){'."\n";
		echo "\t\t".'$("a.zoombox").zoombox({'."\n";
		echo "\t\t\t".'theme : ' . '\'' .$theme. '\'' . ','."\n";
		echo "\t\t\t".'opacity : '.$opacity.','."\n";
		echo "\t\t\t".'duration : '.$duration.','."\n";
		echo "\t\t\t".'animation : '.$animation.','."\n";
		echo "\t\t\t".'width : '.$width.','."\n";
		echo "\t\t\t".'height : '.$height.','."\n";
		echo "\t\t\t".'gallery : '.$gallery.','."\n";
		echo "\t\t\t".'autoplay : '.$autoplay.','."\n";
		echo "\t\t\t".'overflow : '.$overflow."\n";
		echo "\t\t".'});'."\n";
		echo "\t".'});'."\n";
		echo '</script>'."\n";
	}

}
?>

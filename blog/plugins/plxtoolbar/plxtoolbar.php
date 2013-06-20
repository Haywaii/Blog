<?php
/**
 * Classe plxtoolbar
 *
 * @package PLX
 * @version 1.2
 * @date	01/07/2011
 * @author	Stephane F
 **/
class plxtoolbar extends plxPlugin {

	/**
	 * Constructeur de la classe
	 *
	 * @return	null
	 * @author	Stéphane F.
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# Ajoute les hooks nécessaires pour le fonctionnement de la plxToolbar
		$this->addHook('AdminTopEndHead', 'AdminTopEndHead');
		$this->addHook('AdminFootEndBody', 'AdminFootEndBody');

		# Hook dédié à la toolbar pour les customs buttons
		$this->addHook('plxToolbarCustomsButtons', 'getCustomsButtons');
	}
	
	/**
	 * Méthode qui récupere les boutons utilisateurs dans le dossier cutom.buttons
	 *
	 * @return	stdio
	 * @author	Stéphane F.
	 **/
	public function getCustomsButtons() {
		$str='';
		# On regarde s'il y a des boutons personnels à ajouter dans la plxtoolbar
		if(is_dir(PLX_PLUGINS.'plxtoolbar/custom.buttons/')) {
			$buttons = plxGlob::getInstance(PLX_PLUGINS.'plxtoolbar/custom.buttons/');
			if($aFiles = $buttons->query('/button.(.*).php$/')) {
				foreach($aFiles as $button) {
					echo '<?php include(\''.PLX_PLUGINS.'plxtoolbar/custom.buttons/'.$button.'\'); ?>';
				}
			}
		}
	}

	/**
	 * Méthode qui ajoute les déclarations dans la partie <head> de l'administration
	 *
	 * @return	stdio
	 * @author	Stéphane F.
	 **/
	public function AdminTopEndHead() {
		echo "\n".'<link rel="stylesheet" type="text/css" href="'.PLX_PLUGINS.'plxtoolbar/plxtoolbar/style.css" media="screen" />';
		echo "\n\t".'<script type="text/javascript" src="'.PLX_PLUGINS.'plxtoolbar/plxtoolbar/plxtoolbar.js"></script>';
		$langfile = PLX_PLUGINS.'plxtoolbar/plxtoolbar/lang/'.$this->default_lang.'.js';
		if(is_file($langfile))
		echo "\n\t".'<script type="text/javascript" src="'.$langfile.'"></script>';
	}

	/**
	 * Méthode qui ajoute les déclarations dans le footer de l'administration
	 *
	 * @return	stdio
	 * @author	Stéphane F.
	 **/
	public function AdminFootEndBody() {
		echo '<?php eval($plxAdmin->plxPlugins->callHook(\'plxToolbarCustomsButtons\', \'addCustomButtons\')); ?>';
		echo "\n\t".'<script type="text/javascript">plxToolbar.init(\''.PLX_PLUGINS.'plxtoolbar/'.'\');</script>'."\n";
	}

}
?>
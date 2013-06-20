<?php
/**
 * Classe scrollToTop
 *
 * @version 1.0
 * @date	22/01/2012
 * @author	Patrice Blondel, Cyril MAGUIRE
 **/
class scrollToTop extends plxPlugin {
	
	
	/**
	 * Constructeur de la classe
	 *
	 * @return	null
	 * @author	Cyril MAGUIRE 
	 **/	
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);
		
		# Ajouts des hooks
		$this->addHook('ThemeEndHead', 'ThemeEndHead');
		$this->addHook('ThemeEndBody', 'ThemeEndBody');
	}
	
	/**
	 * Méthode pour afficher la mise en page 
	 *
	 * @author Cyril MAGUIRE
	 */
	public function ThemeEndHead()
	{
		echo "\t".'<link rel="stylesheet" type="text/css" href="'.PLX_PLUGINS.'scrollToTop/scrolltotop.css" media="screen" />'."\n";
	}
	
	/**
	 * Méthode pour afficher le javascript
	 *
	 * @author Cyril MAGUIRE
	 */
	public function ThemeEndBody()
	{
		echo "\t".'<script type="text/javascript">
				/* <![CDATA[ */
				!window.jQuery && document.write(\'<script  type="text/javascript" src="'.PLX_PLUGINS.'scrollToTop/jquery-1.7.1.min.js"><\/script>\');
				/* !]]> */
			</script>'."\n";
		echo "\t".'<script type="text/javascript" src="'.PLX_PLUGINS.'scrollToTop/scrolltotop.js"></script>'."\n";
	}
}	
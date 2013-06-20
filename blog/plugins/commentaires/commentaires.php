<?php
/**
 * Plugin commentaires
 *
 * @package	PLX
 * @version	1.0
 * @date	03/01/2012
 * @author	Cyril MAGUIRE
 **/
class commentaires extends plxPlugin {

	/**
	 * Constructeur de la classe commentaires
	 *
	 * @param	default_lang	langue par défaut utilisée par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);
		
		# Déclarations des hooks
		$this->addHook('AdminArticleInitData', 'AdminArticleInitData');
	}

	/**
	 * Méthode qui ajoute modifie le statut par défaut du sélecteur d'autorisation des commentaires dans un article
	 *
	 * @return	stdio
	 * @author	Cyril MAGUIRE
	 **/	
	public function AdminArticleInitData() {
	$string =<<<END
<?php \$allow_com = 0;?>
END;
echo $string;

	}

}
?>
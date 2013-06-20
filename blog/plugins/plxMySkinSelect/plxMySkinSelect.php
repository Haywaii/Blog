<?php
/**
 * Plugin MySkinSelect
 *
 * @package	PLX
 * @version	1.0.2
 * @date	20/10/2011
 * @author 	Stephane F
 *
 * Utilisation:
 * Ajouter dans le fichier sidebar.php les lignes suivantes pour afficher le sélecteur de thèmes
 *
 *		<h3>Th&egrave;mes</h3>
 *		<?php $plxShow->callHook('MySkinSelect') ?>
 *
 **/
class plxMySkinSelect extends plxPlugin {

	public $aSkins = array(); # tableau contenant la liste des themes

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par défaut utilisée par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# Déclarations des hooks
		$this->addHook('Index', 'Index');
		$this->addHook('MySkinSelect', 'MySkinSelect');

	}

	/**
	 * Méthode qui récupère la liste des thèmes dans le dossier /thèmes
	 *
	 * @author	Stephane F.
	 *
	 **/
	public function getSkins() {

		$files = plxGlob::getInstance(PLX_ROOT.'themes', true);
		if($styles = $files->query("/[a-z0-9-_\.\(\)]+/i")) {
			foreach($styles as $k=>$v) {
				if(substr($v,0,7) != 'mobile.')	$this->aSkins[$v] = $v;
			}
		}
	}

	/**
	 * Méthode qui applique le changement de thèmes
	 *
	 * @author	Stephane F.
	 *
	 **/
	public function Index() {
		$plxMotor = plxMotor::getInstance();
		if(!empty($_GET['style'])) { # Si le formulaire est soumis
			setcookie('style', $_GET['style'], time()+3600*24*2);
			header('Location: '.$plxMotor->urlRewrite());
			exit;
		}

		echo '<?php $plxMotor->style = plxUtils::getValue($_COOKIE["style"], $plxMotor->style); ?>';

	}

	/**
	 * Méthode qui affiche la liste déroulante de tous les thèmes disponibles
	 *
	 * @return	stdio
	 * @author	Stephane F.
	 *
	 **/
	public function MySkinSelect() {
		# Récuperation de la liste des thèmes
		$this->getSkins();

		# Mise en place du sélecteur
		$plxMotor = plxMotor::getInstance();
		$current_skin = plxUtils::getValue($_COOKIE['style'], $plxMotor->style);
		echo '<form action="'.$plxMotor->urlRewrite().'" method="get">';
		plxUtils::printSelect('style', $this->aSkins, $current_skin);
		echo '<input type="submit" value="ok"/>';
		echo '</form>';

	}
}
?>
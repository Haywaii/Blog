<?php
/**
 * Plugin plxMySearch
 * @author	Stephane F
 **/
class plxMySearch extends plxPlugin {

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par défaut
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

        # appel du constructeur de la classe plxPlugin (obligatoire)
        parent::__construct($default_lang);

		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);

        # déclaration des hooks
		$this->addHook('plxShowConstruct', 'plxShowConstruct');
        $this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
        $this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
		$this->addHook('plxShowPageTitle', 'plxShowPageTitle');
		$this->addHook('SitemapStatics', 'SitemapStatics');
		$this->addHook('ThemeEndHead', 'ThemeEndHead');
		$this->addHook('MySearchForm', 'form');
    }

	/**
	 * Méthode de traitement du hook plxShowConstruct
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
    public function plxShowConstruct() {
		# infos sur la page statique
		$string  = "if(\$this->plxMotor->mode=='".$this->getParam('url')."') {";
		$string .= "	\$array = array();";
		$string .= "	\$array[\$this->plxMotor->cible] = array(
			'name'		=> '".$this->getParam('mnuName')."',
			'menu'		=> '',
			'url'		=> 'search',
			'readable'	=> 1,
			'active'	=> 1,
			'group'		=> ''
		);";
		$string .= "	\$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
		$string .= "}";
		echo "<?php ".$string." ?>";
	}

	/**
	 * Méthode de traitement du hook plxMotorPreChauffageBegin
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
    public function plxMotorPreChauffageBegin() {

		$template = $this->getParam('template')==''?'static.php':$this->getParam('template');

		$string = "
		if(\$this->get && preg_match('/^".$this->getParam('url')."\/?/',\$this->get)) {
			\$this->mode = '".$this->getParam('url')."';
			\$this->cible = '../../plugins/plxMySearch/form';
			\$this->template = '".$template."';
			return true;
		}
		";

		echo "<?php ".$string." ?>";
    }

	/**
	 * Méthode de traitement du hook plxShowStaticListEnd
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
    public function plxShowStaticListEnd() {

		# ajout du menu pour accèder à la page de recherche
		if($this->getParam('mnuDisplay')) {
			echo "<?php \$class = \$this->plxMotor->mode=='".$this->getParam('url')."'?'active':'noactive'; ?>";
			echo "<?php array_splice(\$menus, ".($this->getParam('mnuPos')-1).", 0, '<li><a class=\"static '.\$class.'\" href=\"'.\$this->plxMotor->urlRewrite('?".$this->getParam('url')."').'\">".$this->getParam('mnuName')."</a></li>'); ?>";
		}
    }

	/**
	 * Méthode qui renseigne le titre de la page dans la balise html <title>
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxShowPageTitle() {
		echo '<?php
			if($this->plxMotor->mode == "'.$this->getParam('url').'") {
				echo plxUtils::strCheck($this->plxMotor->aConf["title"])." - ".plxUtils::strCheck($this->plxMotor->plxPlugins->aPlugins["plxMySearch"]["instance"]->getLang("L_PAGE_TITLE"));
				return true;
			}
		?>';
	}

	/**
	 * Méthode qui référence la page de recherche dans le sitemap
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function SitemapStatics() {
		echo '<?php
		echo "\n";
		echo "\t<url>\n";
		echo "\t\t<loc>".$plxMotor->urlRewrite("?'.$this->getParam('url').'")."</loc>\n";
		echo "\t\t<changefreq>monthly</changefreq>\n";
		echo "\t\t<priority>0.8</priority>\n";
		echo "\t</url>\n";
		?>';
	}

	/**
	 * Méthode qui ajoute le fichier css dans le fichier header.php du thème
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function ThemeEndHead() {
		echo "\t".'<link rel="stylesheet" type="text/css" href="'.PLX_PLUGINS.'plxMySearch/style.css" media="screen" />'."\n";
	}

	/**
	 * Méthode statique qui affiche le formulaire de recherche
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public static function form($title=false) {

		# récuperation d'une instance de plxMotor
		$plxMotor = plxMotor::getInstance();
		$plxPlugin = $plxMotor->plxPlugins->getInstance('plxMySearch');
		$searchword = '';
		if(!empty($_POST['searchfield'])) {
			$searchword = strtolower(htmlspecialchars(trim($_POST['searchfield'])));
		}
	?>

	<form role="search" action="<?php echo $plxMotor->urlRewrite('?'.$plxPlugin->getParam('url')) ?>" method="post" id="searchform" >
		
		<?php if($title) : ?>

		<p class="searchtitle"><?php $plxPlugin->lang('L_FORM_SEARCHFIELD') ?>&nbsp;:</p>
		
		<?php endif; ?>
		
		<div id="fieldset">
			<input type="text" class="search_box" name="searchfield" id="s" value="<?php echo $searchword == '' ? $plxPlugin->getParam('frmLibButton') : $searchword; ?>"  onfocus="if (this.value == '<?php echo $plxPlugin->getParam('frmLibButton') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo $plxPlugin->getParam('frmLibButton') ?>';}"/>
			<input type="<?php echo (is_file(str_replace('plugins/plxMySearch/plxMySearch.php',$plxMotor->aConf['racine_themes'].$plxMotor->style.'/styles/images/search.gif',__FILE__))) ? 'image" src="'. $plxMotor->urlRewrite($plxMotor->aConf['racine_themes'].$plxMotor->style).'/styles/images/search.gif' : 'submit'?>" class="submit" name="searchbutton" id="searchsubmit" value="<?php echo $plxPlugin->getParam('frmLibButton') ?>"/>
		</div>
	</form>
	<?php
	}
}
?>
<?php
/**
 * Plugin plxEditor
 *
 * @package	PLX
 * @version	1.2
 * @date	20/10/2011
 * @author	Stephane F
 **/
class plxeditor extends plxPlugin {

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

		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);

		# Déclarations des hooks
		$this->addHook('AdminTopEndHead', 'AdminTopEndHead');
		$this->addHook('AdminFootEndBody', 'AdminFootEndBody');
		$this->addHook('AdminArticlePrepend', 'AdminArticlePrepend'); # conversion des liens pour le preview d'un article
		
		$this->addHook('plxAdminEditArticle', 'plxAdminEditArticle');
		$this->addHook('AdminArticleTop', 'AdminArticleTop');
		$this->addHook('AdminArticlePreview', 'AdminArticlePreview');

	}

	#----------

	/**
	 * Méthode qui convertit les liens absolus en liens relatifs pour les images et les documents
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxAdminEditArticle() {
		echo "<?php \$content['chapo'] = str_replace('../../plugins/plxeditor/', 'plugins/plxeditor/', \$content['chapo']); ?>";
		echo "<?php \$content['content'] = str_replace('../../plugins/plxeditor/', 'plugins/plxeditor/', \$content['content']); ?>";
	}
	/**
	 * Méthode qui convertit les liens absolus en liens relatifs pour les images et les documents
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function AdminArticlePreview() {
		echo "<?php \$art['chapo'] = str_replace('../../plugins/plxeditor/', 'plugins/plxeditor/', \$art['chapo']); ?>";
		echo "<?php \$art['content'] = str_replace('../../plugins/plxeditor/', 'plugins/plxeditor/', \$art['content']); ?>";
	}
	
	/**
	 * Méthode qui convertit les liens relatifs en liens absolus pour les images et les documents
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function AdminArticleTop() {
		echo "<?php \$chapo = str_replace('plugins/plxeditor/', '../../plugins/plxeditor/', \$chapo); ?>";
		echo "<?php \$content = str_replace('plugins/plxeditor/', '../../plugins/plxeditor/', \$content); ?>";
	}
	
	/**
	 * Méthode appelé lors du préview d'un article: conversion des liens des images et des documents
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function AdminArticlePrepend() {
		if(!empty($_POST['preview'])) {
			echo "<?php \$_POST['chapo'] = str_replace('../../'.\$plxAdmin->aConf['images'], \$plxAdmin->aConf['images'], \$_POST['chapo']); ?>";
			echo "<?php \$_POST['content'] = str_replace('../../'.\$plxAdmin->aConf['images'], \$plxAdmin->aConf['images'], \$_POST['content']); ?>";
			echo "<?php \$_POST['chapo'] = str_replace('../../'.\$plxAdmin->aConf['documents'], \$plxAdmin->aConf['documents'], \$_POST['chapo']); ?>";
			echo "<?php \$_POST['content'] = str_replace('../../'.\$plxAdmin->aConf['documents'], \$plxAdmin->aConf['documents'], \$_POST['content']); ?>";
		}
	}

	#----------

	/**
	 * Méthode du hook AdminTopEndHead
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function AdminTopEndHead() {
		echo '<link type="text/css" rel="stylesheet" href="'.PLX_PLUGINS.'plxeditor/plxeditor/css/plxeditor.css" />'."\n";
		echo '<script type="text/javascript" src="'.PLX_PLUGINS.'plxeditor/plxeditor/plxeditor.js"></script>'."\n";
	}

	/**
	 * Méthode du hook AdminFootEndBody
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function AdminFootEndBody() {?>

	<script type="text/javascript">
	<!--
		<?php echo "<?php \$images = \$plxAdmin->aConf['images'] . (\$plxAdmin->aConf['userfolders'] ? \$_SESSION['user'].'/' : '') ?>" ?>
		<?php echo "<?php \$docs = \$plxAdmin->aConf['documents'] . (\$plxAdmin->aConf['userfolders'] ? \$_SESSION['user'].'/' : '') ?>" ?>
		if(document.getElementById('id_chapo')) { editor_chapo = new PLUXML.editor.create('editor_chapo', 'id_chapo', '<?php echo "<?php echo \$images ?>" ?>','<?php echo "<?php echo \$docs ?>" ?>'); }
		if(document.getElementById('id_content')) { editor_content = new PLUXML.editor.create('editor_content', 'id_content', '<?php echo "<?php echo \$images ?>" ?>','<?php echo "<?php echo \$docs ?>" ?>'); }
	-->
	</script>

	<?php
	}
}
?>
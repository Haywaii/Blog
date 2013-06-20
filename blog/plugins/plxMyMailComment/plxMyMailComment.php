<?php
/**
 * Plugin plxMyMailComment
 *
 * @package	PLX
 * @version	1.1
 * @date	20/10/2011
 * @author	Stephane F and contributors
 **/
class plxMyMailComment extends plxPlugin {

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

		# droits pour accèder à la page config.php et admin.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);
		$this->setAdminProfil(PROFIL_ADMIN);

		# ajout du hook
		$this->addHook('plxMotorDemarrageNewCommentaire', 'plxMotorDemarrageNewCommentaire');
		$this->addHook('AdminTopBottom', 'AdminTopBottom');
	}

	public function AdminTopBottom() {

		$string = '
		if(empty($plxAdmin->aUsers[$_SESSION["user"]]["email"])) {
			echo "<p class=\"warning\">Plugin MyMailComment<br />'.$this->getLang("L_WARNING_NO_EMAIL").'</p>";
			plxMsg::Display();
		}';
		echo '<?php '.$string.' ?>';

	}

	/**
	 * Méthode qui envoie un mail à l'auteur de l'article
	 *
	 * @return	stdio
	 * @author	Stéphane F, Deevad
	 **/
	public function plxMotorDemarrageNewCommentaire() {

		$string = '
		$article_author = $this->plxRecord_arts->f("author");
		$article_author_email = $this->aUsers[$article_author]["email"];
		if($article_author_email!="" AND ($retour[0]=="c" OR $retour=="mod")) {
			$cc = "'.$this->getParam('cc').'";
			$bcc = "'.$this->getParam('bcc').'";
			$eSubject = "'.$this->getLang("L_EMAIL_NEW_COMMENT").' ".$this->plxRecord_arts->f("title");
			$eBody  = "'.$this->getLang("L_EMAIL_NEW_COMMENT_BY").' <strong>".$_POST["name"]."</strong><br /><br />";
			$eBody .= $_POST["content"]."<br /><br />";
			$eBody .= "-----------<br />";
			if($retour[0]=="c") {
				$eBody .= "'.$this->getLang("L_EMAIL_READ_ONLINE").' : <a href=\"".$url."/#".$retour."\">".$this->plxRecord_arts->f("title")."</a>";
				$eBody .= "<br /><a href=\"".$this->racine."core/admin/comments.php\">'.$this->getLang("L_EMAIL_MANAGE_COMMENTS").'</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
				$eBody .= "<a href=\"".$this->racine."core/admin/comment_new.php?c=".$this->plxRecord_arts->f("numero").".".substr($retour,1)."\">'.$this->getLang("L_EMAIL_DIRECT_ANSWER").'</a>";
			} else {
				$eBody .= "'.$this->getLang("L_EMAIL_WAITING_MODERATION").'<br />";
				$eBody .= "<a href=\"".$this->racine."core/admin/comments.php?sel=offline&page=1\">'.$this->getLang("L_EMAIL_MANAGE_COMMENTS").'</a>&nbsp;&nbsp;&nbsp;";
			}
			plxUtils::sendMail($this->aConf["title"], "no-reply@domaine.com", $article_author_email, $eSubject, $eBody, "html", $cc, $bcc);
		}
		';
		echo '<?php '.$string.' ?>';
	}

}
?>
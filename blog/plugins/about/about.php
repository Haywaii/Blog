<?php
/**
 * Classe about
 *
 * @version 1.5
 * @date	12/06/2012
 * @author	Cyril MAGUIRE
 **/
class about extends plxPlugin {
	
	public $aboutList = array(); # Tableau des données concernant les informations générales

	/**
	 * Constructeur de la classe
	 *
	 * @return	null
	 * @author	Cyril MAGUIRE 
	 **/	
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);
		
		# droits pour accèder à la page config.php du plugin
		$this->setAdminProfil(PROFIL_ADMIN, PROFIL_MANAGER);
		$this->setConfigProfil(PROFIL_ADMIN, PROFIL_MANAGER);
		
		# Ajouts des hooks
		$this->addHook('ThemeEndBody', 'ThemeEndBody');
		$this->addHook('ThemeEndHead', 'ThemeEndHead');

		$this->addHook('aboutTitle', 'aboutTitle');
		$this->addHook('about', 'about');
		$this->addHook('see', 'see');

		$this->addHook('plxShowLastCatList', 'plxShowLastCatList');
		$this->addHook('prevNext', 'prevNext');

		$this->addHook('commentsTree', 'commentsTree');
		$this->addHook('displayChildren', 'displayChildren');

		$this->addHook('plxMotorNewCommentaire', 'plxMotorNewCommentaire');
		$this->addHook('plxMotorAddCommentaireXml', 'plxMotorAddCommentaireXml');
		$this->addHook('plxMotorParseCommentaire', 'plxMotorParseCommentaire');

		$this->addHook('plxShowTagList', 'plxShowTagList');
		$this->addHook('artCatName', 'artCatName');

		$this->addHook('AdminArticleSidebar', 'AdminArticleSidebar');
		$this->addHook('plxAdminEditArticleXml', 'plxAdminEditArticleXml');
		$this->addHook('AdminArticlePreview', 'AdminArticlePreview');
		$this->addHook('AdminArticlePostData', 'AdminArticlePostData');
		$this->addHook('AdminArticleParseData', 'AdminArticleParseData');
		$this->addHook('plxMotorParseArticle','plxMotorParseArticle');
	}

	/**
	 * Méthode qui insère les informations générales
	 *
	 * @return	stdio
	 * @author	Cyril MAGUIRE
	 **/	
	public function about() {
		$this->getAbout(PLX_ROOT.$this->getParam('about'));
		if(!$this->aboutList['infos']) {
			return; 
		}else{
			echo $this->aboutList['infos'];
		}
		
	}

	/**
	 * Méthode qui insère les informations générales
	 *
	 * @return	stdio
	 * @author	Cyril MAGUIRE
	 **/	
	public function aboutTitle() {
		echo plxUtils::strCheck($this->getParam('pub_title'))."\n";
	}
	
	/**
	 * Méthode qui récupère les infos enregistrées dans le fichier data/configuration/about.xml
	 */
	public function getAbout($filename) {
		
		if(!is_file($filename)) return;
		
		# Mise en place du parseur XML
		$data = implode('',file($filename));
		$parser = xml_parser_create(PLX_CHARSET);
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,0);
		xml_parse_into_struct($parser,$data,$values,$iTags);
		xml_parser_free($parser);
		if(isset($iTags['about']) AND isset($iTags['alt'])) {
			$nb = sizeof($iTags['alt']);
			$size=ceil(sizeof($iTags['about'])/$nb);
			for($i=0;$i<$nb;$i++) {
				$attributes = $values[$iTags['about'][$i*$size]]['attributes'];
				$number = $attributes['number'];
				# Recuperation du texte alternatif
				$this->aboutList[$number]['alt']=plxUtils::getValue($values[$iTags['alt'][$i]]['value']);
				# Recuperation du nom de la description
				$this->aboutList[$number]['description']=plxUtils::getValue($values[$iTags['description'][$i]]['value']);
				# Recuperation de l'url
				$this->aboutList[$number]['url']=plxUtils::getValue($values[$iTags['url'][$i]]['value']);
				# Recuperation du lien externe (si disponible)
				$this->aboutList[$number]['extLink']=plxUtils::getValue($values[$iTags['extLink'][$i]]['value']);
				
			}
		}
		if(isset($iTags['infos'][0])) {
				$this->aboutList['infos'] = plxUtils::getValue(plxUtils::getValue($values[$iTags['infos'][0]]['value']));
		}
		
	}
	
	/**
	 * Méthode qui édite le fichier XML du plugin about selon le tableau $content
	 *
	 * @param	content	tableau multidimensionnel du plugin about
	 * @param	action	permet de forcer la mise à jour du fichier
	 * @return	string
	 * @author	Stephane F
	 **/
	public function editAboutlist($content, $action=false) {

		$save = $this->aboutList;
		
		# suppression
		if(!empty($content['selection']) AND $content['selection']=='delete' AND isset($content['idAbout'])) {
			foreach($content['idAbout'] as $about_id) {
				unset($this->aboutList[$about_id]);
				$action = true;
			}
		}
		
		# mise à jour de la liste des catégories
		elseif(!empty($content['update'])) {
			foreach($content['aboutNum'] as $about_id) {
				$about_name = $content[$about_id.'_alt'];
				if($about_name!='') {
					$this->aboutList[$about_id]['alt'] = $about_name;
					$this->aboutList[$about_id]['url'] = $content[$about_id.'_url'];
					$this->aboutList[$about_id]['description'] = $content[$about_id.'_description'];
					$this->aboutList[$about_id]['extLink'] = $content[$about_id.'_extLink'];
					$this->aboutList[$about_id]['ordre'] = intval($content[$about_id.'_ordre']);
					$action = true;
				}
			}
			if (!empty($content['content'])) {
				$this->aboutList['cpinfos'] = $content['content'];
				$action = true;
			}

		}    
		# On va trier les clés selon l'ordre choisi
		if(sizeof($this->aboutList)>0) uasort($this->aboutList, create_function('$a, $b', 'return $a["ordre"]>$b["ordre"];'));
		
		# sauvegarde
		if($action) {
			# On génére le fichier XML
			$xml = "<?xml version=\"1.0\" encoding=\"".PLX_CHARSET."\"?>\n";
			$xml .= "<document>\n";
			$xml .= "\t<infos><![CDATA[".plxUtils::cdataCheck($this->aboutList['cpinfos'])."]]></infos>\n";
			foreach($this->aboutList as $about_id => $about) {
				if ($about_id != 'cpinfos') {
				$xml .= "\t<about number=\"".$about_id."\">";
				$xml .= "<alt><![CDATA[".plxUtils::cdataCheck($about['alt'])."]]></alt>";
				$xml .= "<description><![CDATA[".plxUtils::cdataCheck($about['description'])."]]></description>";
				$xml .= "<url><![CDATA[".plxUtils::cdataCheck($about['url'])."]]></url>";
				$xml .= "<extLink><![CDATA[".plxUtils::cdataCheck($about['extLink'])."]]></extLink>";
				$xml .= "</about>\n";
				}
			}
			$xml .= "</document>";
			
			# On écrit le fichier
			if(plxUtils::write($xml, PLX_ROOT.$this->getParam('about')))
				return plxMsg::Info(L_SAVE_SUCCESSFUL);
			else {
				$this->aboutList = $save;
				return plxMsg::Error(L_SAVE_ERR.' '.$filename);
			}			
		}
	}

	/**
	 * Méthode qui permet d'afficher des images liées ou non
	 *
	 * @param $Format string format de sortie
	 * @return string 
	 * @author Cyril MAGUIRE
	 */
	public function see($Format) {		
		
		$plxMotor = plxMotor::getInstance();

		$this->getAbout(PLX_ROOT.$this->getParam('about'));
		if(!$this->aboutList) { return; }
		
		if(!isset($Format[0])) { $format = '<li><a href="#extLink" title="#description"><img src="#url" alt="#alt" width="#width" height="#height"/></a></li>'; } else { $format = $Format[0]; }
		if(!isset($Format[1])) { $format2 = ''; } else { $format2 = $Format[1]; }
		foreach($this->aboutList as $k => $link) {
			if ($k != 'infos') {
				if (!empty($link['extLink'])){
					$row = str_replace('"#extLink"','"'.plxUtils::strCheck($link['extLink']).'" onclick="window.open(this.href);return false;"',$format);
					$row = str_replace('#url',(strpos($link['url'],'http') === false ? $plxMotor->urlRewrite($link['url']):$link['url']),$row);
				} else {
					if(empty($format2)) { $format2 = '<li><img src="#url" alt="#alt" width="#width" height="#height" title="#description"/></li>'; }
					$row = str_replace('#url',(strpos($link['url'],'http') === false ? $plxMotor->urlRewrite($link['url']):$link['url']),$format2);
				}
			
			$row = str_replace('#description',plxUtils::strCheck($link['description']),$row);
			$row = str_replace('#title',plxUtils::strCheck($link['title']),$row);
			$row = str_replace('#alt',plxUtils::strCheck($link['alt']),$row);
			$row = str_replace('#width',plxUtils::strCheck($this->getParam('width')),$row);
			$row = str_replace('#height',plxUtils::strCheck($this->getParam('height')),$row);
			echo $row."\n\t\t";
			}
		}
		
	}

	public function AdminArticleSidebar(){
		echo "<?php 
		\$formatsArt = array(
			'standard'=>'Standard',
			'aside'=>'Aside',
			'audio'=>'Audio',
			'chat'=>'Chat',
			'gallery'=>'Gallerie',
			'image'=>'Image',
			'link'=>'Lien',
			'quote'=>'Citation',
			'status'=>'Statut',
			'video'=>'Vidéo');
		
		if (\$plxAdmin->aConf['style'] == 'dailypost') :
			echo '<p>Format :</p>'.\"\n\";
			foreach(\$formatsArt as \$id => \$name) {
				\$selected = (\$id == \$format) ? ' checked=\"checked\"' : '';
				echo '<input type=\"radio\" id=\"format-'.\$id.'\" name=\"format\"'.\$selected.' value=\"'.\$id.'\" />'.\"\n\";
				echo '<label for=\"format-'.\$id.'\">&nbsp;'.plxUtils::strCheck(\$name).'</label><br />'.\"\n\";
			}
			
		endif;
		?>";
	}

	public function plxAdminEditArticleXml(){
		echo "<?php
		\$xml .= \"\t\".'<format><![CDATA['.plxUtils::cdataCheck(plxUtils::getValue(\$content['format'])).']]></format>'.\"\n\";
		?>";
	}

	public function AdminArticlePreview(){
		echo "<?php \$art['format'] = \$_POST['format'];?>";
	}
	public function AdminArticlePostData(){
		echo "<?php \$format = \$_POST['format'];?>";
	}
	public function AdminArticleParseData(){
		echo "<?php \$format = \$result['format'];?>";
	}
	public function plxMotorParseArticle(){
		echo "<?php \$art['format'] = (isset(\$iTags['format']))?trim(\$values[ \$iTags['format'][0] ]['value']):''; ?>";
	}

	/**
	 * Méthode qui affiche la liste des catégories actives, avec la liste des articles associés.
	 * Si la variable $extra est renseignée, un lien vers la
	 * page d'accueil (nommé $extra) sera mis en place en première
	 * position.
	 *
	 * @param	$extra	string nom du lien vers la page d'accueil
	 * @param	$format	string format du texte pour chaque catégorie (variable : #cat_id, #cat_status, #cat_url, #cat_name, #art_nb)
	 *			ou
	 *			$format array(
	 *					$format[0] string format du texte pour chaque catégorie (variable : #cat_id, #cat_status, #cat_url, #cat_name, #art_nb)
	 * 					$format[1] integer nombre d'articles maximum à afficher dans les sous-menu
	 *			)
     * @param	include string	liste des catégories à afficher séparées par le caractère | (exemple: 001|003)
     * @param	exclude string	liste des catégories à ne pas afficher séparées par le caractère | (exemple: 002|003)
 	 * @return	stdout
	 * @scope	global
	 * @author	Anthony GUÉRIN, Florent MONTHEL, Stephane F, Cyril MAGUIRE
	 */
	public function plxShowLastCatList(){
		$string =<<<END
		if (is_array(\$format)) {
			\$f = \$format[0];
			\$nbSousMenu = intval(\$format[1]);
			\$format = \$f;
			unset(\$f);
		} else {
			\$nbSousMenu = 0;
		}
		# Si on a la variable extra, on affiche un lien vers la page d'accueil (avec \$extra comme nom)
		if(\$extra != '') {
			\$name = str_replace('#cat_id','cat-home',\$format);
			\$name = str_replace('#cat_url',\$this->plxMotor->urlRewrite(),\$name);
			\$name = str_replace('#cat_name',plxUtils::strCheck(\$extra),\$name);
			\$name = str_replace('#cat_status',(\$this->catId()=='home'?'active':'noactive'), \$name);
			\$name = str_replace('#art_nb','',\$name);
			echo \$name."\n\t\t\t";
		}
		if(\$this->plxMotor->aCats) {
			foreach(\$this->plxMotor->aCats as \$k=>\$v) {
				\$in = (empty(\$include) OR preg_match('/('.\$include.')/', \$k));
				\$ex = (!empty(\$exclude) AND preg_match('/('.\$exclude.')/', \$k));
				if(\$in AND !\$ex) {
				if((\$v['articles']>0 OR \$this->plxMotor->aConf['display_empty_cat']) AND (\$v['menu']=='oui') AND \$v['active']) { # On a des articles
					\$k = intval(\$k);
					if (\$nbSousMenu != 0) :
					ob_start();
					\$this->lastArtList('<li class="#art_status"><a href="#art_url"><span>#art_title</span></a></li>',\$nbSousMenu,\$k);
					\$sousmenu = ob_get_clean();
					if (strlen(\$sousmenu) != 0):
			            \$sousmenu = '
				<ul>
					'.str_replace('</li><li', '</li>'."\n\t\t\t\t\t".'<li', \$sousmenu).'
				</ul>
			</li>';
					endif;
					else :
						\$sousmenu = '</li>';
					endif;

					# On modifie nos motifs
						\$name = str_replace('#cat_id','menu_cat-'.\$k,\$format);
						\$name = str_replace('#cat_url',\$this->plxMotor->urlRewrite('?categorie'.\$k.'/'.\$v['url']),\$name);
						\$name = str_replace('#cat_name',plxUtils::strCheck(\$v['name']),\$name);
						if (\$this->mode() == 'article' && in_array(\$k,explode(',',\$this->plxMotor->plxRecord_arts->f('categorie')))) {
							\$name = str_replace('#cat_status','active', \$name);
						}else {
							\$name = str_replace('#cat_status',(\$this->catId()==\$k?'active':'noactive'), \$name);
						}
						\$name = str_replace('#art_nb',\$v['articles'],\$name);
						\$name = str_replace('</li>',\$sousmenu,\$name);
						echo \$name;
					}
				}
			} 
		}
		return true;
END;
		echo '<?php '.$string.'?>';
	}

	/**
	 * Méthode qui affiche la liste de tous les tags.
	 *
	 * @param	format	format du texte pour chaque tag (variable : #tag_status, #tag_url, #tag_name, #nb_art)
	 * @param	max		nombre maxi de tags à afficher
	 * @return	stdout
	 * @scope	global
	 * @author	Stephane F
	 **/
	public function plxShowTagList() {
		$string =<<<END
		\$datetime = date('YmdHi');
		\$array=array();
		# On verifie qu'il y a des tags
		if(\$this->plxMotor->aTags) {
			# On liste les tags sans créer de doublon
			foreach(\$this->plxMotor->aTags as \$idart => \$tag) {
				if(isset(\$this->plxMotor->activeArts[\$idart]) AND \$tag['date']<=\$datetime AND \$tag['active']) {
					if(\$tags = array_map('trim', explode(',', \$tag['tags']))) {
						foreach(\$tags as \$tag) {
							if(\$tag!='') {
								\$t = plxUtils::title2url(\$tag);
								if(!isset(\$array['_'.\$tag])) {
									\$array['_'.\$tag]=array('name'=>\$tag,'url'=>\$t,'count'=>1);
								}
								else
									\$array['_'.\$tag]['count']++;
							}
						}
					}
				}
			}
			array_multisort(\$array);
			if(\$max!='') \$array=array_slice(\$array, 0, intval(\$max), true);
		}
		# On affiche la liste
		\$size=0;
		foreach(\$array as \$tagname => \$tag) {
			\$name = str_replace('#tag_id','tag-'.\$size++,\$format);
			\$name = str_replace('#tag_url',\$this->plxMotor->urlRewrite('?tag/'.\$tag['url']),\$name);
			\$name = str_replace('#tag_name',plxUtils::strCheck(\$tag['name']),\$name);
			\$name = str_replace('#nb_art',\$tag['count'],\$name);
			\$name = str_replace('#tag_status',((\$this->plxMotor->mode=='tags' AND \$this->plxMotor->cible==\$tag['url'])?'active':'noactive'), \$name);
			if (\$tag['count'] > 10) \$freq = 'max';
			else \$freq = \$tag['count'];
			\$name = str_replace('#tag_link', 'tag-link-'.\$freq, \$name);
			echo \$name;
		}
		return true;
END;
		echo '<?php '.$string.'?>';
	}

	/**
	 * Méthode qui crée un nouveau commentaire pour l'article $artId
	 *
	 * @param	artId	identifiant de l'article en question
	 * @param	content	tableau contenant les valeurs du nouveau commentaire
	 * @return	string
	 * @author	Florent MONTHEL, Stéphane F
	 * @modification Cyril MAGUIRE ajout de l'index "parent"
	 **/
	public function plxMotorNewCommentaire() {
		$string = "
		# On verifie que le capcha est correct
		if(\$this->aConf['capcha'] == 0 OR \$content['rep2'] == sha1(\$content['rep'])) {
			if(!empty(\$content['name']) AND !empty(\$content['content'])) { # Les champs obligatoires sont remplis
				\$comment=array();
				\$comment['type'] = 'normal';
				\$comment['author'] = plxUtils::strCheck(trim(\$content['name']));
				\$comment['content'] = plxUtils::strCheck(trim(\$content['content']));
				if (isset(\$_POST['comment_parent'])) {
				\$comment['parent'] = plxUtils::strCheck(trim(\$_POST['comment_parent']));
				}
				# On verifie le mail
				\$comment['mail'] = (plxUtils::checkMail(trim(\$content['mail'])))?trim(\$content['mail']):'';
				# On verifie le site
				\$comment['site'] = (plxUtils::checkSite(\$content['site'])?\$content['site']:'');
				# On recupere l'adresse IP du posteur
				\$comment['ip'] = plxUtils::getIp();
				# On genere le nom du fichier selon l'existence ou non d'un fichier du meme nom
				\$time = time();
				\$i = 0;
				do { # On boucle en testant l'existence du fichier (cas de plusieurs commentaires/sec pour un article)
					\$i++;
					if(\$this->aConf['mod_com']) # On modere le commentaire => underscore
						\$comment['filename'] = '_'.\$artId.'.'.\$time.'-'.\$i.'.xml';
					else # On publie le commentaire directement
						\$comment['filename'] =\$artId.'.'.\$time.'-'.\$i.'.xml';
				} while(file_exists(\$comment['filename']));
				# On peut creer le commentaire
				if(\$this->addCommentaire(\$comment)) { # Commentaire OK
					if(\$this->aConf['mod_com']) # En cours de moderation
						return 'mod';
					else # Commentaire publie directement, on retourne son identifiant
						return 'c'.\$time.'-'.\$i;
				} else { # Erreur lors de la création du commentaire
					return L_NEWCOMMENT_ERR;
				}
			} else { # Erreur de remplissage des champs obligatoires
				return L_NEWCOMMENT_FIELDS_REQUIRED;
			}
		} else { # Erreur de verification capcha
			return L_NEWCOMMENT_ERR_ANTISPAM;
		}
		return true;
		";
		echo "<?php ".$tring."?>";
	}

	/**
	 * Méthode qui crée physiquement le fichier XML du commentaire
	 *
	 * @param	comment	array avec les données du commentaire à ajouter
	 * @return	booléen
	 * @author	Anthony GUÉRIN, Florent MONTHEL et Stéphane F
	 * @modification Cyril MAGUIRE ajout de l'item "parent"
	 **/
	public function plxMotorAddCommentaireXml() {
		$string = <<<END
		if (isset(\$_POST['comment_parent'])) {
		\$xml .= "\t<parent><![CDATA[".plxUtils::cdataCheck(\$_POST['comment_parent'])."]]></parent>\n";
		}
END;
		echo "<?php ".$string."?>";

	}

	/**
	 * Méthode qui parse le commentaire du fichier $filename
	 *
	 * @param	filename	fichier du commentaire à parser
	 * @return	array
	 * @author	Florent MONTHEL
	 * @modification Cyril MAGUIRE ajout de l'item "parent"
	 **/
	public function plxMotorParseCommentaire() {
		$string = <<<END
		\$com['parent'] = trim(\$values[ \$iTags['parent'][0] ]['value']);
END;
		echo "<?php ".$string."?>";
	}

	/**
	 * Méthode qui crée l'arborescence et les liens de parenté entre les commentaires d'un article (qui répond à qui)
	 * 
	 * @param $results array tableau de tous les commentaires de l'article
	 * @param $depth integer profondeur de l'arborescence (de 1 à 4)
	 * @return array
	 * @author Cyril MAGUIRE
	 */
	public function commentsTree($params=array()) {
			$family = $isChild = array();
			//Niveau de profondeur de l'arborescence
			if (!isset($params[1]) || $params[1]<=0 || $params[1]>4)
				$profondeur = (integer)$this->getParam('depth');
			else
				$profondeur = $params[1];

			for ($i=0;$i<$profondeur;$i++){
				foreach($params[0] as $idCom => $v) {
					if ($i != 0 && !empty($v['parent']) && in_array($v['parent'],$family[$i-1])) {
						$family[$i][] = $idCom+1;
						if (!in_array($idCom+1, $isChild)) {
							$isChild[$v['parent']][] = $idCom+1;
						}
					}elseif (empty($v['parent']) && !in_array($idCom+1, $family[0])) {
						$family[0][] = $idCom+1;
					}
				}
			} 
			$plxShow = plxShow::getInstance();
			ob_start();
			$plxShow->artAuthorEmail();
			$artAuthorEmail = ob_get_clean();
			ob_start();
			$plxShow->ArtUrl();
			$ArtUrl = ob_get_clean();
			return array(
				'family'	=> $family,		//Défini dans quel niveau se situe un commentaire
				'isChild'	=> $isChild, 	//Défini si un commentaire a un statut d'enfant,
				'artAuthorEmail' => $artAuthorEmail,
				'ArtUrl'	=> $ArtUrl
				);
	}

	/**
	 * Méthode permettant de mettre en forme les commentaires enfants.
	 * A modifier selon votre theme
	 *
	 * @param $enfant integer index du commentaire à afficher
	 * @param $results array tableau de l'ensemble des commentaires
	 * @param $emailAuthor string email de l'auteur de l'article commenté
	 * @param $ArtUrl string url de l'article commenté
	 * @param $artId integer index de l'article commenté
	 * @param $oddAlt string parité du commentaire (pair ou impair)
	 * @param $reply bool indique s'il y a possibilité de répondre au commentaire affiché
	 * @param $li bool indique s'il faut fermer les balises de liste
	 *
	 * @return string le commentaire formaté
	 *
	 * @author Cyril MAGUIRE
	 */
	public function formatComments($format='defaut',$enfant, $results, $emailAuthor, $ArtUrl, $artId, $oddAlt, $reply, $li){
		$plxShow = plxShow::getInstance();
		switch ($format) :
			case 'dailypost' :
		?>

			<!-- # comment #-->
			<ul class="children">
				<li class="comment  <?php echo $oddAlt == 'alt' ? 'odd' : $oddAlt;?>  vcard" id="li-comment-<?php echo $enfant;?>">
				    <div id="c<?php echo $results['numero']; ?>" class="entry  <?php echo $oddAlt;?>">
				    	<header class="entry-header-comments">
							<h3 class="entry-title-comments">
							<?php if($results['type']=='admin') : # si commentaire de type admin ?>
						
							<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($emailAuthor)) ?>?s=32&d=mm" alt="Gravatar" class="photo avatar avatar-32 photo avatar-default" height="32px" width="32px" />
							<?php else: # si commentaire d'un visiteur ?>
						
							<img src="http://www.gravatar.com/avatar/<?php echo md5( strtolower($results['mail']) ) ?>?s=32&d=mm" alt="Gravatar" class="photo avatar avatar-32 photo avatar-default" height="32px" width="32px" />
							<?php endif; ?>

							<span class="fn"><?php echo (!empty($results['site']) ? '<a href="'.$results['site'].'" title="'.$results['site'].'">'.$results['author'].'</a>' : $results['author']);?></span> 
							<span class="says"><?php $plxShow->lang('SAID') ?> </span> <span class="comment-date"><?php echo plxDate::formatDate($results['date'],'#day #num_day #month #num_year(4) &#64; #hour:#minute'); ?></span>
							<span class="perma"><a class="comment-permalink" href="<?php $ArtUrl; ?>#<?php echo 'c'.$results['numero'] ?>" title="#<?php echo $enfant ?>">Permalink</a></span>
							</h3>
							<h3 class="entry-title-comments">&nbsp;</h3>
						</header>
						<div class="entry-content" id="comment-<?php echo $enfant;?>">
							<p><?php echo $results['content'];?></p>
						</div>
						<div class="reply">
			        		 <?php if ($reply):?>

          						<a class="comment-reply-link" href="<?php $ArtUrl; ?>#<?php echo 'c'.$results['numero'];?>" title="#<?php echo $enfant ?>" onclick="return addComment.moveForm('comment-<?php echo $enfant;?>', '<?php echo $enfant;?>', 'respond', '<?php echo $artId;?>')"><?php $this->lang('REPLY')?></a>
          					<?php endif; ?>

          				</div>
          			</div>
          		</li>
						<?php if ($li) : ?>
				
			</ul>
			<!-- # comment #-->

						<?php endif;
			break;
			default:
		?>

			<ul class="children">
				   <li class="comment  <?php echo $oddAlt == 'alt' ? 'odd' : $oddAlt;?>" id="li-comment-<?php echo $enfant;?>">
				      <div id="c<?php echo $results['numero']; ?>" class="entry  <?php echo $oddAlt;?>">
				      <div class="comment-head">
				      		<div class="avatar">
							<?php if($results['type']=='admin') : # si commentaire de type admin ?>
						
							<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($emailAuthor)) ?>?s=32&d=mm" alt="Gravatar" class="photo avatar avatar-32 photo avatar-default" height="32px" width="32px" />
							<?php else: # si commentaire d'un visiteur ?>
						
							<img src="http://www.gravatar.com/avatar/<?php echo md5( strtolower($results['mail']) ) ?>?s=32&d=mm" alt="Gravatar" class="photo avatar avatar-32 photo avatar-default" height="32px" width="32px" />
							<?php endif; ?>

							</div>
							<div class="user-meta">
				            	<span class="name"><?php echo (!empty($results['site']) ? '<a href="'.$results['site'].'" title="'.$results['site'].'">'.$results['author'].'</a>' : $results['author']);?></span>
				            	<span class="date"><?php echo plxDate::formatDate($results['date'],'#day #num_day #month #num_year(4) &#64; #hour:#minute'); ?></span>
				            	<span class="edit"></span>
				            	<span class="perma"><a class="comment-permalink" href="<?php $ArtUrl; ?>#<?php echo 'c'.$results['numero'] ?>" title="#<?php echo $enfant ?>">Permalink</a></span>
				            </div>
				          	<div class="clear"></div>
				    	</div> 

				    	<div class="comment-entry" id="comment-<?php echo $enfant;?>">
			        		<p><?php echo $results['content'];?></p>
			        		<div class="reply">
			        		 <?php if ($reply):?>

          						<a class="comment-reply-link" href="<?php $ArtUrl; ?>#<?php echo 'c'.$results['numero'];?>" title="#<?php echo $enfant ?>" onclick="return addComment.moveForm('comment-<?php echo $enfant;?>', '<?php echo $enfant;?>', 'form', '<?php echo $artId;?>')"><?php $this->lang('REPLY')?></a>
          					<?php endif; ?>

          					</div>
						</div>
						</div>
						<?php if ($li) : ?>

					</li>
				</ul>
						<?php endif;
			break;
		endswitch;
	}

	/**
	 * Méthode permettant l'affichage des commentaires
	 * 
	 * @param $params array tableau contenant les différents paramètres nécessaires
	 *			$results array Tableau contenant l'ensemble des commentaires
	 *			$family array Tableau contenant les niveaux des commentaires
	 *			$isChild array Tableau déterminant si un commentaire est l'enfant d'un autre
	 *			$plxCom string Index du commentaire
	 * 			$artAuthorEmail string Email de l'auteur principal de l'article					
	 * 			$ArtUrl string Url de l'article					
	 * 			$artId integer Index de l'article					
	 * 			$display array Tableau permettant de savoir si un commentaire a déjà été affiché
	 *			$depth string Niveau de profondeur de l'arborescence (de 1 à 4)
	 * @return $display array Tableau permettant de savoir si un commentaire a déjà été affiché
	 * @author Cyril MAGUIRE
	 */
	public function displayChildren($params){
			$results = $params[0];
			$family = $params[1];
			$isChild = $params[2];
			$plxCom = $params[3];
			$artAuthorEmail = $params[4];
			$ArtUrl = $params[5];
			$artId = $params[6];
			$display = (array)$params[7];
			if (!isset($params[8])) {
				$format = 'defaut';
			} else {
				$format = $params[8];
			}
			if (!isset($params[9])) {
				$depth = (integer)$this->getParam('depth');
			} else {
				$depth = $params[9];
			}
			

			//Commentaires de premier niveau
			for ($i=1;$i<$depth;$i++):
			foreach ($isChild as $cle => $Parent) :
				if ($cle == $plxCom) :
			foreach ($Parent as $k => $enfant) :
				if (in_array($results[$enfant-1]['parent'], $family[$i-1]) && !in_array($results[$enfant-1]['id'], $family[3]) && !in_array($enfant, $display)) :
					$display[] = $enfant;
					$oddAlt = ( empty( $oddAlt ) ) ? ' alt ' : '';
					$this->formatComments(
							$format,//Le format d'affichage des commentaires enfants
							$enfant,//L'index du commentaire enfant
							$results[$enfant-1],//Le tableau des données du commentaire enfant
							$artAuthorEmail,//L'email de l'auteur de l'article
							$ArtUrl,//L'url de l'article
							$artId,//L'index de l'article
							$oddAlt,//Parité
							($depth > 2) ? true : false,//Les réponses à ce niveau de l'arborescence sont-elles possibles ?
							($depth > 1) ? false : true);//Faut-il fermer les balises de liste ?
			//Commentaires de deuxième niveau
			if (array_key_exists($enfant, $isChild)) :
					foreach ($isChild[$enfant] as $ke => $ve) :
						if (!in_array($ve, $display)) :
						$display[] = $ve;
						$oddAlt = ( empty( $oddAlt ) ) ? ' alt ' : '';
						$this->formatComments(
							$format,//Le format d'affichage des commentaires enfants
							$ve,//L'index du commentaire enfant
							$results[$ve-1],//Le tableau des données du commentaire enfant
							$artAuthorEmail,//L'email de l'auteur de l'article
							$ArtUrl,//L'url de l'article
							$artId,//L'index de l'article
							$oddAlt,//Parité
							($depth > 3) ? true : false,//Les réponses à ce niveau de l'arborescence sont-elles possibles ?
							($depth > 2) ? false : true);//Faut-il fermer les balises de liste ?
				//Commentaires de troisième niveau (niveau max)
				if (array_key_exists($ve, $isChild)) :
					foreach ($isChild[$ve] as $index => $val) :
						if (!in_array($val, $display)) :
						$display[] = $val;
						$oddAlt = ( empty( $oddAlt ) ) ? ' alt ' : '';
						$this->formatComments(
							$format,//Le format d'affichage des commentaires enfants
							$val,//L'index du commentaire enfant
							$results[$val-1],//Le tableau des données du commentaire enfant
							$artAuthorEmail,//L'email de l'auteur de l'article
							$ArtUrl,//L'url de l'article
							$artId,//L'index de l'article
							$oddAlt,//Parité
							false,//Les réponses à ce niveau de l'arborescence sont-elles possibles ?
							true);//Faut-il fermer les balises de liste ?
						endif;
					endforeach;
				endif;?>

					</li>
				</ul>
			<?php 
						endif;
					endforeach;
				endif;?>

					</li>
				</ul>
			<?php 
				endif;
			endforeach;
				endif;
			endforeach;
			endfor;
			return $display;
	}

	/**
	 * Méthode qui affiche un peu de css pour masquer un lien dans le formulaire de réponse
	 */
	public function ThemeEndHead()
	{
		echo '
		<style type="text/css" media="screen">
			#cancel-comment-reply-link{display: none;}
		</style>
';
	}

	/**
	 * Méthode qui affiche un fichier js nécesaire pour préciser quel est le commentaire parent du commentaire en cours de rédaction
	 */
	public function ThemeEndBody()
	{
		$plxShow = plxShow::getInstance();
		if ($plxShow->mode() == 'article') :
		echo '<script type="text/javascript" src="'.PLX_PLUGINS.'about/js/addComment.js"></script>'; 
		endif;
	}

	/**
	 * Méthode qui ne retourne que le nom des categories auxquelles un article appartient
	 */
	public function artCatName() {
		$plxMotor = plxMotor::getInstance();
		$name = '';
		# Initialisation de notre variable interne
		$catIds = explode(',', $plxMotor->plxRecord_arts->f('categorie'));
		foreach ($catIds as $idx => $catId) {
			# On verifie que la categorie n'est pas "home"
			if($catId != 'home') {
				# On va verifier que la categorie existe
				if(isset($plxMotor->aCats[ $catId ])) {
					# On recupere les infos de la categorie
					$name .= strtolower(trim(str_replace(' ','-',plxUtils::strCheck($plxMotor->aCats[ $catId ]['name']))));
				} else { # La categorie n'existe pas
					$name .= strtolower(L_UNCLASSIFIED);
				}
			} else { # Categorie "home"
				$name .= strtolower(L_HOMEPAGE);
			}
			if ($idx!=sizeof($catIds)-1) $name.' ';
		}
		return $name;
	}

	/**
	 * Méthode permettant d'afficher les liens vers les articles précédent et suivant d'une catégorie ou de l'ensemble des articles
	 * 
	 * @param $params array Tableau des paramètres d'affichage
	 *				  $mode bool Si true, n'affiche que deux articles au plus d'une catégorie
	 *							 Si false affiche deux articles au plus de tous les articles
	 *				  $formatPrev string optional Le format d'affichage des liens "précédent"
	 *				  $formatNext string optional le format d'affichage des liens "suivant"
	 * @return $links string Au maximum, deux liens
	 * @author Stéphane F., DanielSan, Cyril MAGUIRE
	 */
	public function prevNext($params) {
		$mode = (bool)$params[0];
		if (isset($params[1])) {
			$formatPrev = $params[1];
		} else {
			$formatPrev = '<a href="#prevUrl" title="#prevTitle" rel="prev">&laquo; <span>#prevArt</span></a> ';
		}
		if (isset($params[2])) {
			$formatNext = $params[2];
		} else {
			$formatNext = ' <a href="#nextUrl" title="#nextTitle" rel="next"><span>#nextArt</span> &raquo;</a>';
		}

		$plxShow = plxShow::getInstance();
		$ordre = preg_match('/asc/',$plxShow->plxMotor->tri)?'sort':'rsort';
		$links = '';
		
		if($mode AND $plxShow->catId()!= "home") { // Des articles parmi les articles d'une catégorie
			$ID_CAT = str_pad ($plxShow->catId(), 3, '0', STR_PAD_LEFT);
			$aFiles = $plxShow->plxMotor->plxGlob_arts->query('/[0-9]{4}.['.$ID_CAT.']*.[0-9]{3}.[0-9]{12}.[a-z0-9-]+.xml$/','art',$ordre,0,false,'before');
		} else { // Des articles parmi tous les articles
			$aFiles = $plxShow->plxMotor->plxGlob_arts->query('/[0-9]{4}.[home|0-9,]*.[0-9]{3}.[0-9]{12}.[a-z0-9-]+.xml$/','art',$ordre,0,false,'before');
		}

		$key = array_search(basename($plxShow->plxMotor->plxRecord_arts->f('filename')), $aFiles);
		$prevUrl = $prev = isset($aFiles[$key-1])? $aFiles[$key-1] : false;
		$nextUrl = $next = isset($aFiles[$key+1])? $aFiles[$key+1] : false;

		$plxGlob_arts = clone $plxShow->plxMotor->plxGlob_arts;

			if($prev AND preg_match('/([0-9]{4}).[home|0-9,]*.[0-9]{3}.[0-9]{12}.([a-z0-9-]+).xml$/',$prev,$capture))
				$prevUrl=$plxShow->plxMotor->urlRewrite('?article'.intval($capture[1]).'/'.$capture[2]);
				if ($prev){
					$art = $plxShow->plxMotor->parseArticle(PLX_ROOT.$plxShow->plxMotor->aConf['racine_articles'].$prev);
					$nextTitle = STRIP_TAGS($art['title']);
				}
			if($next AND preg_match('/([0-9]{4}).[home|0-9,]*.[0-9]{3}.[0-9]{12}.([a-z0-9-]+).xml$/',$next,$capture))
				$nextUrl=$plxShow->plxMotor->urlRewrite('?article'.intval($capture[1]).'/'.$capture[2]);
				if ($next) {
					$art = $plxShow->plxMotor->parseArticle(PLX_ROOT.$plxShow->plxMotor->aConf['racine_articles'].$next);
					$prevTitle = STRIP_TAGS($art['title']);
				}
			if($ordre=='rsort') { 
				$dummy=$prevUrl; $prevUrl=$nextUrl; $nextUrl=$dummy; 
			}
			if($prevUrl) {
				$links = str_replace('#prevUrl', $prevUrl, $formatPrev);
				$links = str_replace('#prevTitle', $prevTitle, $links);
				$links = str_replace('#prevArt', $this->getlang('L_PREV_ART'), $links);
				$links = str_replace('#prevImg', $prevImg, $links);
			}
			if($nextUrl) {
				$links .= str_replace('#nextUrl', $nextUrl, $formatNext);
				$links = str_replace('#nextTitle', $nextTitle, $links);
				$links = str_replace('#nextArt', $this->getlang('L_NEXT_ART'), $links);
			}
			return $links;
	}

}
?>
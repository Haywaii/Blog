<?php if(!defined('PLX_ROOT')) exit; ?>
<style type="text/css">
pre {
	font-size:14px;
	border: 1px solid #999;
	padding: 20px;
	white-space: pre-wrap;
	word-wrap: break-word;
	background-color: #FFF;
}
.red {color:red;}
</style>

<h2>Aide</h2>
<p>Fichier d&#039;aide du plugin About</p>

<p>&nbsp;</p>
<h2>Installation</h2>
<p>Pensez &agrave; activer le plugin.<br/>
Editez le fichier template dans lequel vous souhaitez afficher les données ("sidebar.php" par exemple, ou "home.php"). Ajoutez y le code suivant &agrave; l&#039;endroit o&ugrave; vous souhaitez voir apparaitre les données "A propos" :</p>
<pre>
&lt;h2&gt;&lt;?php eval($plxShow-&gt;callHook(&#039;aboutTitle&#039;)); ?&gt;&lt;/h2&gt;
&lt;?php eval($plxShow-&gt;callHook(&#039;about&#039;)); ?&gt;
</pre>
<p>Pour afficher les images publicitaires (ou autre), ajoutez le code suivant dans la sidebar où vous souhaitez les voir apparaitre</p>
<pre>
&lt;ul&gt;
	&lt;?php eval($plxShow-&gt;callHook(&#039;see&#039;)); ?&gt;
&lt;/ul&gt;
</pre>
<p>Pour afficher les liens "Article précédent" et "Article suivant", il faut utiliser le hook "prevNext". 
	<br/>
Celui-ci prend trois arguments facultatifs : </p>
<ul>
	<li>le premier indique si l'on ne cherche que dans les articles d'une catégorie ou dans tous les articles;</li>
	<li>le deuxième indique le format d'affichage du lien "Article précédent";</li>
	<li>le troisième le format d'affichage du lien "Article suivant". </li>
</ul>
<p>Vous avez donc plusieurs possibilités. Par exemple :</p>
<pre>
&lt;?php echo $plxShow->callHook('prevNext',array(true); // ou false ?&gt;
<br/>
&lt;?php echo $plxShow->callHook('prevNext',array(
	true,//ou false
	'&lt;a href="#prevUrl" rel="prev"&gt;&lt;img src="'.$plxMotor->urlRewrite().'themes/myTheme/images/prev.png" alt="&amp;laquo;" title="#prevTitle"&gt;&lt;/a&gt;'."\n\t\t\t\t\t",
	'&lt;a href="#nextUrl" rel="next"&gt;&lt;img src="'.$plxMotor->urlRewrite().'themes/myTheme/images/next.png" alt="&amp;raquo;" title="#nextTitle"&gt;&lt;/a&gt;'
	)); 
?&gt;
</pre>
<h2>Commentaires imbriqués</h2>
<p><strong>Avertissement : ces modifications s'adressent à ceux qui n'ont pas peur de mettre les mains dans le cambouis !</strong></p>
<p>Pour permettre l'imbrication des commentaires, il est nécessaire de modifier quelque peu la page "commentaires.php" avec les codes suivants :</p>
<pre>
&lt;?php
	//Tableau de tous les commentaires de l'article
	$results = $plxShow->plxMotor->plxRecord_coms->result;
	//Création de l'arborescence des commentaires
	$r = $plxShow-&gt;callHook('commentsTree',array($results));

	//A placer dans le lien de réponse
	onclick="return addComment.moveForm('comment-&lt;?php echo $plxCom;?&gt;', '&lt;?php echo $plxCom;?&gt;', 'form', '&lt;?php echo $plxShow-&gt;artId();?&gt;')"

	 //Affichage des commentaires enfants
	$display = $plxShow-&gt;callHook(&#039;displayChildren&#039;,array($results, $r['family'], $r['isChild'], $plxCom, $r['artAuthorEmail'], $r['ArtUrl'], $artId, $display)); 
?&gt;
</pre>
<p>Le formulaire doit également être modifié :</p>
<pre>
&lt;input type="hidden" name="comment_post_ID" value="&lt;?php echo $plxShow-&gt;artId();?&gt;" /&gt;
&lt;/p&gt;
&lt;input type="hidden" name="comment_post_ID" value="&lt;?php echo $plxShow-&gt;artId();?&gt;" id="comment_post_ID" /&gt;
&lt;input type="hidden" name="comment_parent" id="comment_parent" value="0" /&gt;
</pre>
<p>Bien entendu, <strong>la css doit être également adaptée au thème</strong>, mais normalement l'imbrication doit être visible vu que cela fait intervenir des listes imbriquées.</p>
<h2>Exemple de page</h2>
<p>Plutôt que d'expliquer où placer les codes, voici un exemple de page (les données qui varient du thème par défaut sont en rouge et sont nécessaire à un affichage correct de l'arborescence des commentaires) :</p>
<p><u>fichier :</u> commentaires.php</p>
<pre>
&lt;?php if(!defined('PLX_ROOT')) exit; ?&gt;

&lt;?php if($plxShow-&gt;plxMotor-&gt;plxRecord_coms):?&gt;

&lt;div id="comments"&gt;
&lt;h2&gt;&lt;?php echo $plxShow-&gt;artNbCom() ?&gt;&lt;/h2&gt;
&lt;ol class="commentlist"&gt;

	&lt;?php
		<span class="red">
		//Tableau de tous les commentaires de l'article
		$results = $plxShow-&gt;plxMotor-&gt;plxRecord_coms-&gt;result;
		//Création de l'arborescence des commentaires
		$r = $plxShow-&gt;callHook('commentsTree',array($results));</span>

	while($plxShow-&gt;plxMotor-&gt;plxRecord_coms-&gt;loop()): # On boucle sur les commentaires 
	
	<span class="red">//Index du commentaire
	$plxCom = $plxShow-&gt;plxMotor-&gt;plxRecord_coms-&gt;i+1;
	//Commentaires initiaux (réponses à l'article)
	if (in_array($plxCom,$r['family'][0])) :
	?&gt;</span>

	&lt;li class="comment &lt;?php  $parite = (($i%2) == 0 ? 'even' : 'odd');echo $parite;$i++;?&gt; thread-&lt;?php echo $parite;?&gt; depth-1" id="li-comment-&lt;?php echo $plxCom;?&gt;"&gt;
		&lt;div class="comment-head"&gt;
     		&lt;div class="user-meta"&gt;
            	&lt;span class="name"&gt;&lt;?php $plxShow-&gt;comAuthor('link'); ?&gt;&lt;/span&gt;
        		&lt;span class="date"&gt;&lt;?php $plxShow-&gt;comDate('#day #num_day #month #num_year(4) &#64; #hour:#minute'); ?&gt;&lt;/span&gt;
        		&lt;span class="edit"&gt;&lt;/span&gt;
        		&lt;span class="perma"&gt;&lt;a class="num-com" href="&lt;?php $plxShow-&gt;comUrl() ?&gt;" title="#&lt;?php echo $plxCom ?&gt;"&gt;PERMALINK&lt;/a&gt;&lt;/span&gt;
			&lt;/div&gt;
			&lt;div class="clear"&gt;&lt;/div&gt;
		&lt;/div&gt;
		&lt;div id="&lt;?php $plxShow-&gt;comId(); ?&gt;"&gt;
		&lt;div class="comment-entry"  id="comment-&lt;?php echo $plxCom;?&gt;"&gt;
           &lt;p class="content_com type-&lt;?php $plxShow-&gt;comType(); ?&gt;"&gt;&lt;?php $plxShow-&gt;comContent() ?&gt;&lt;/p&gt;
           &lt;div class="reply"&gt;
           		&lt;?php if ($depth &gt; 1): ?&gt;

      			&lt;a class="comment-reply-link" href="&lt;?php $plxShow-&gt;comUrl() ?&gt;" title="#&lt;?php echo $plxCom ?&gt;" <span class="red">onclick="return addComment.moveForm('comment-&lt;?php echo $plxCom;?&gt;', '&lt;?php echo $plxCom;?&gt;', 'form', '&lt;?php echo $plxShow-&gt;artId();?&gt;')"</span>&gt;&lt;?php echo $plxShow-&gt;lang('REPLY')?&gt;&lt;/a&gt;
      			&lt;?php endif; ?&gt;

			&lt;/div&gt;
		&lt;/div&gt;
		&lt;/div&gt;
		<span class="red">
		&lt;?php endif;

		//Affichage des commentaires enfants
		$display = $plxShow-&gt;callHook('displayChildren',array($results, $r['family'], $r['isChild'], $plxCom, $r['artAuthorEmail'], $r['ArtUrl'], $artId, $display));

		if (in_array($plxCom,$r['family'][0])) :?&gt;

	&lt;/li&gt;
	&lt;?php endif;</span>
	endwhile; # Fin de la boucle sur les commentaires ?&gt;

&lt;/ol&gt;
&lt;/div&gt;&lt;!-- end #comments_wrap --&gt;
&lt;?php endif; ?&gt;

&lt;?php if($plxShow-&gt;plxMotor-&gt;plxRecord_arts-&gt;f('allow_com') AND $plxShow-&gt;plxMotor-&gt;aConf['allow_com']): ?&gt;

&lt;div id="form"&gt;

&lt;h2&gt;&lt;?php $plxShow-&gt;lang('WRITE_A_COMMENT') ?&gt;&lt;/h2&gt;

&lt;?php if(isset($_SESSION['msgcom']) && !empty($_SESSION['msgcom']) && $_SESSION['msgcom'] != L_COM_IN_MODERATION):?&gt;
	&lt;p class="error"&gt;
		&lt;?php $plxShow-&gt;comMessage(); ?&gt;
	&lt;/p&gt;

&lt;?php endif;?&gt;
&lt;?php if(isset($_SESSION['msgcom']) && !empty($_SESSION['msgcom']) && $_SESSION['msgcom'] == L_COM_IN_MODERATION):unset($_SESSION['malvoyant'])?&gt;
	&lt;p class="success"&gt;
		&lt;?php $plxShow-&gt;comMessage(); ?&gt;.&lt;br/&gt;Merci.
	&lt;/p&gt;
&lt;?php endif;?&gt;

<span class="red">&lt;div class="cancel-comment-reply"&gt;
	&lt;small&gt;&lt;a rel="nofollow" id="cancel-comment-reply-link" href="&lt;?php (intval($plxShow-&gt;plxMotor-&gt;plxRecord_arts-&gt;f('nb_com')) != 0)? $plxShow-&gt;comUrl() : ''; ?&gt;"&gt;&lt;?php $plxShow-&gt;lang('CANCEL_REPLY') ?&gt;&lt;/a&gt;&lt;/small&gt;
&lt;/div&gt;</span>

&lt;form action="&lt;?php $plxShow-&gt;artUrl(); ?&gt;" method="post" id="commentform"&gt;
	&lt;p&gt;&lt;input type="text" name="name" id="author"  value="&lt;?php $plxShow-&gt;comGet('name',''); ?&gt;" size="22" tabindex="1" /&gt;
	&lt;label for="author"&gt;&lt;small&gt;&lt;?php $plxShow-&gt;lang('NAME') ?&gt;&lt;/small&gt;&lt;/label&gt;&lt;/p&gt;

	&lt;p&gt;&lt;input type="text" name="mail" id="email" value="&lt;?php $plxShow-&gt;comGet('mail',''); ?&gt;" size="22" tabindex="2" /&gt;
	&lt;label for="email"&gt;&lt;small&gt;&lt;?php $plxShow-&gt;lang('EMAIL') ?&gt;&lt;/small&gt;&lt;/label&gt;&lt;/p&gt;

	&lt;p&gt;&lt;input type="text" name="site" id="url" value="&lt;?php $plxShow-&gt;comGet('site',''); ?&gt;" size="22" tabindex="3" /&gt;
	&lt;label for="url"&gt;&lt;small&gt;&lt;?php $plxShow-&gt;lang('WEBSITE') ?&gt;&lt;/small&gt;&lt;/label&gt;&lt;/p&gt;

	&lt;p&gt;&lt;textarea name="content" id="comment" style="width:97%;" rows="10" tabindex="4"&gt;&lt;?php $plxShow-&gt;comGet('content',''); ?&gt;&lt;/textarea&gt;&lt;/p&gt;

	&lt;?php if($plxShow-&gt;plxMotor-&gt;aConf['capcha']): ?&gt;
	&lt;label for="id_rep"&gt;&lt;strong&gt;&lt;?php echo $plxShow-&gt;lang('ANTISPAM_WARNING') ?&gt;&lt;/strong&gt;&nbsp;:&lt;/label&gt;
	&lt;p&gt;&lt;?php $plxShow-&gt;capchaQ(); ?&gt;&nbsp;:&nbsp;&lt;input id="id_rep" name="rep" type="text" size="10" /&gt;&lt;/p&gt;
	&lt;input name="rep2" type="hidden" value="&lt;?php $plxShow-&gt;capchaR(); ?&gt;" /&gt;
	&lt;?php endif; ?&gt;

	&lt;p&gt;&lt;input name="submit" type="submit" id="submit" tabindex="5" value="&lt;?php $plxShow-&gt;lang('SEND') ?&gt;" /&gt;
	<span class="red">&lt;input type="hidden" name="comment_post_ID" value="&lt;?php echo $plxShow-&gt;artId();?&gt;" /&gt;
	&lt;/p&gt;
	&lt;input type="hidden" name="comment_post_ID" value="&lt;?php echo $plxShow-&gt;artId();?&gt;" id="comment_post_ID" /&gt;
	&lt;input type="hidden" name="comment_parent" id="comment_parent" value="0" /&gt;</span>
&lt;/form&gt;

&lt;div class="fix"&gt;&lt;/div&gt;
&lt;/div&gt; &lt;!-- end #respond --&gt;

&lt;?php else: ?&gt;

	&lt;p&gt;&lt;?php $plxShow-&gt;lang('COMMENTS_CLOSED') ?&gt;.&lt;/p&gt;
&lt;?php endif; # Fin du if sur l'autorisation des commentaires ?&gt;
</pre>
<p>Pour affiner le format d'affichage des commentaires enfants, il est possible de modifier le rendu de la méthode formatComments (fichier "about.php" dans le dossier plugins/about).</p>
<pre>
&lt;?php
public function formatComments($enfant, $results, $emailAuthor, $ArtUrl, $artId, $reply, $li){
?&gt;

&lt;ul class="children"&gt;
	   &lt;li class="comment odd alt" id="li-comment-&lt;?php echo $enfant;?&gt;"&gt;
	    &lt;div id="c&lt;?php echo $results['numero']; ?&gt;"&gt;
	    &lt;div class="comment-head"&gt;
		&lt;div class="user-meta"&gt;
	            	&lt;span class="name"&gt;&lt;?php echo (!empty($results['site']) ? '&lt;a href="'.$results['site'].'" title="'.$results['site'].'"&gt;'.$results['author'].'&lt;/a&gt;' : $results['author']);?&gt;&lt;/span&gt;
	            	&lt;span class="date"&gt;&lt;?php echo plxDate::formatDate($results['date'],'#day #num_day #month #num_year(4) &#64; #hour:#minute'); ?&gt;&lt;/span&gt;
	            	&lt;span class="edit"&gt;&lt;/span&gt;
	            	&lt;span class="perma"&gt;&lt;a class="comment-permalink" href="&lt;?php $ArtUrl; ?&gt;#&lt;?php echo 'c'.$results['numero'] ?&gt;" title="#&lt;?php echo $enfant ?&gt;"&gt;Permalink&lt;/a&gt;&lt;/span&gt;
	        &lt;/div&gt;
	          	&lt;div class="clear"&gt;&lt;/div&gt;
	    &lt;/div&gt; 

	    	&lt;div class="comment-entry" id="comment-&lt;?php echo $enfant;?&gt;"&gt;
        		&lt;p&gt;&lt;?php echo $results['content'];?&gt;&lt;/p&gt;
        		&lt;div class="reply"&gt;
        		 <span class="red">&lt;?php if ($reply):?&gt;</span>
				&lt;a class="comment-reply-link" href="&lt;?php $ArtUrl; ?&gt;#&lt;?php echo 'c'.$results['numero'];?&gt;" title="#&lt;?php echo $enfant ?&gt;" <span class="red">onclick="return addComment.moveForm('comment-&lt;?php echo $enfant;?&gt;', '&lt;?php echo $enfant;?&gt;', 'form', '&lt;?php echo $artId;?&gt;')"</span>&gt;&lt;?php $this-&gt;lang('REPLY')?&gt;&lt;/a&gt;
			&lt;?php endif; ?&gt;

			&lt;/div&gt;
		&lt;/div&gt;
	    &lt;/div&gt;
	<span class="red">&lt;?php if ($li) : ?&gt;

		&lt;/li&gt;
	&lt;/ul&gt;
	&lt;?php endif; </span>
	}
?&gt;
</pre>
<h2>A ajouter dans le dossier lang du theme (à créer s'il n'existe pas)</h2>
<p><u>fichier :</u> fr.php</p>
<pre>
&lt;?php

$LANG = array(

# commentaires.php
'SAID'					=&gt; 'a dit',
'WRITE_A_COMMENT'			=&gt; '&Eacute;crire un commentaire',
'NAME'					=&gt; 'Nom',
'WEBSITE'				=&gt; 'Site (facultatif)',
'EMAIL'					=&gt; 'E-mail (facultatif)',
'COMMENT'				=&gt; 'Commentaire',
'CLEAR'					=&gt; 'Effacer',
'SEND'					=&gt; 'Envoyer',
'COMMENTS_CLOSED'			=&gt; 'Les commentaires sont ferm&eacute;s',
'ANTISPAM_WARNING'			=&gt; 'V&eacute;rification anti-spam',
'REPLY'					=&gt; 'Répondre',
'CANCEL_REPLY'				=&gt; 'Cliquez ici pour annuler la réponse.'
);

?&gt;

</pre>
<h2>Affichage des sous-rubriques dans le menu principal</h2>
<p>About permet également d'afficher des sous-rubriques dans le menu principal. Une sous-rubrique correspond à un article qui appartient à une catégorie.</p>
<p>Pour utiliser cette fonctionnalité, il suffit d'utiliser la même méthode que pour le thème principal, mais de fournir en deuxième argument, non pas une chaine de caractères mais un tableau. Le premier index du tableau correspond au format d'affichage des liens (comme habituellement), le deuxième au nombre d'articles à afficher. Les autres arguments de la méthode sont conservés.</p>
<h3>Exemple</h3>
<p>A la place de :</p>
<pre>$plxShow-&gt;catList($plxShow-&gt;getLang('HOME'),'&lt;li class="#cat_status page_item"&gt;&lt;a href="#cat_url" title="#cat_name"&gt;&lt;span&gt;#cat_name&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;'."\n\t\t");</pre>
<p>il faudra écrire :</p>
<pre>$plxShow-&gt;catList($plxShow-&gt;getLang('HOME'),<span class="red">array(</span>'&lt;li class="#cat_status page_item"&gt;&lt;a href="#cat_url" title="#cat_name"&gt;&lt;span&gt;#cat_name&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;'."\n\t\t"<span class="red">,15)</span>);</pre>
<p>Si l'on veut inclure spécifiquement certaines catégories et en exclure d'autres, il suffit de faire comme habituellement :</p>
<pre>$plxShow-&gt;catList($plxShow-&gt;getLang('HOME'),<span class="red">array(</span>'&lt;li class="#cat_status page_item"&gt;&lt;a href="#cat_url" title="#cat_name"&gt;&lt;span&gt;#cat_name&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;'."\n\t\t"<span class="red">,15)</span>,'001|003|004','002|005');</pre>
<p>Si l'on ne souhaite pas afficher de sous-rubriques, on a le choix d'utiliser l'ancienne formule :</p>
<pre>$plxShow-&gt;catList($plxShow-&gt;getLang('HOME'),'&lt;li class="#cat_status page_item"&gt;&lt;a href="#cat_url" title="#cat_name"&gt;&lt;span&gt;#cat_name&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;'."\n\t\t");</pre>
<p>ou de mettre 0 comme nombre de sous-rubriques à afficher</p>
<pre>$plxShow-&gt;catList($plxShow-&gt;getLang('HOME'),<span class="red">array(</span>'&lt;li class="#cat_status page_item"&gt;&lt;a href="#cat_url" title="#cat_name"&gt;&lt;span&gt;#cat_name&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;'."\n\t\t"<span class="red">,0)</span>);</pre>
<p>Enjoy !</p>
<?php if(!defined('PLX_ROOT')) exit; ?>

<!-- #comments -->
<div class="comments">
	<?php if($plxShow->plxMotor->plxRecord_coms): ?>

	<header class="comment-header">
	<h1 class="comment-title">Comments</h1>
	<h2 class="comment-number"><?php echo $plxShow->artNbCom() ?></h2>
	<span></span>
</header>
<!-- #commentList -->
<ul class="commentlist">
		
		<?php
			
			//Tableau de tous les commentaires de l'article
			$results = $plxShow->plxMotor->plxRecord_coms->result;
			//Création de l'arborescence des commentaires
			$r = $plxShow->callHook('commentsTree',array($results));
			ob_start();
			$plxShow->artAuthorEmail();
			$emailAuthor = ob_get_clean();

		while($plxShow->plxMotor->plxRecord_coms->loop()): # On boucle sur les commentaires 
		
		//Index du commentaire
		$plxCom = $plxShow->plxMotor->plxRecord_coms->i+1;
		//Commentaires intiaux (réponses à l'article)
		if (in_array($plxCom,$r['family'][0])) :
		?>

		<li class="comment <?php  $parite = (($i%2) == 0 ? 'even' : 'odd');echo $parite;$i++;?> thread-<?php echo $parite;?> depth-1 vcard" id="comment-<?php echo $plxCom;?>">
		<header class="entry-header-comments">
			<h3 class="entry-title-comments">
		    		<?php if($plxShow->plxMotor->plxRecord_coms->f('type')=='admin') : # si commentaire de type admin ?>
						
					<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($emailAuthor)) ?>?s=32&d=mm" alt="Gravatar" class="photo avatar avatar-32 photo avatar-default" height="32px" width="32px" />
					<?php else: # si commentaire d'un visiteur ?>
						
					<img src="http://www.gravatar.com/avatar/<?php echo md5( strtolower($plxShow->plxMotor->plxRecord_coms->f('mail')) ) ?>?s=32&d=mm" alt="Gravatar" class="photo avatar avatar-32 photo avatar-default" height="32px" width="32px" />
					<?php endif; ?>

		    		<span class="fn"><?php $plxShow->comAuthor('link'); ?></span>
		    		<span class="says"><?php $plxShow->lang('SAID') ?> </span> 
					<span class="comment-date"><?php $plxShow->comDate('#day #num_day #month #num_year(4) &#64; #hour:#minute'); ?></span>

			</h3>
			<h3 class="entry-title-comments">&nbsp;</h3>
		</header>
		<div id="<?php $plxShow->comId(); ?>">
			<div class="entry-content" id="comment-<?php echo $plxCom;?>">
				<p class="content_com type-<?php $plxShow->comType(); ?>"><?php $plxShow->comContent() ?></p>

			</div>
			<div class="reply">
				<?php if ($plxShow->plxMotor->plxPlugins->getInstance("about")->getParam('depth') > 1): ?>
          			<a class="comment-reply-link" href="<?php $plxShow->comUrl() ?>" title="#<?php echo $plxCom ?>" onclick="return addComment.moveForm('comment-<?php echo $plxCom;?>', '<?php echo $plxCom;?>', 'respond', '<?php echo $plxShow->artId();?>')"><?php echo $plxShow->lang('REPLY')?></a>
          			<?php endif; ?>

			</div>
		</div>
		<?php 
		endif;

		if (in_array($plxCom,$r['family'][0])) :?>

		<!-- # comment #-->
		</li>
		<?php endif;
			//Affichage des commentaires enfants
			$display = $plxShow->callHook('displayChildren',array($results, $r['family'], $r['isChild'], $plxCom, $r['artAuthorEmail'], $r['ArtUrl'], $artId, $display, 'Liberty'));

			
		endwhile; # Fin de la boucle sur les commentaires ?>

</ul>
<!-- end #commentList -->

	<?php endif; ?>

	<?php if($plxShow->plxMotor->plxRecord_arts->f('allow_com') AND $plxShow->plxMotor->aConf['allow_com']): ?>

	<div id="respond">
		<header class="page-header">
			<h2 class="page-title"><?php $plxShow->lang('WRITE_A_COMMENT') ?>

				 <small><a rel="nofollow" id="cancel-comment-reply-link" href="<?php (intval($plxShow->plxMotor->plxRecord_arts->f('nb_com')) != 0)? $plxShow->comUrl() : ''; ?>"><?php $plxShow->lang('CANCEL_REPLY') ?></a></small>
			</h2>
			<div class="left-corner"></div>
		</header>

		<?php if(isset($_SESSION['msgcom']) && !empty($_SESSION['msgcom']) && $_SESSION['msgcom'] != L_COM_IN_MODERATION):?>
			<p class="error">
				<?php $plxShow->comMessage(); ?>
			</p>
	
		<?php endif;?>
		<?php if(isset($_SESSION['msgcom']) && !empty($_SESSION['msgcom']) && $_SESSION['msgcom'] == L_COM_IN_MODERATION):unset($_SESSION['malvoyant'])?>
			<p class="success">
				<?php $plxShow->comMessage(); ?>.<br/>Merci.
			</p>
		<?php endif;?>

		<form action="<?php $plxShow->artUrl(); ?>" method="post" id="commentform">
			<p class="comment-notes"><?php $plxShow->lang('EMAIL_NOT_PUBLISHED') ?>
				<span class="required"><a>*</a></span>
			</p>						
			<p class="comment-form-author">
				<label for="author"><?php $plxShow->lang('NAME') ?></label> 
				<span class="required">*</span>
				<input type="text" name="name" id="author"  value="<?php $plxShow->comGet('name',''); ?>" size="30" tabindex="1"  aria-required="true" />
			</p>
			<p class="comment-form-email">
				<label for="email"><?php $plxShow->lang('EMAIL') ?></label>
				<input id="email" name="mail" type="text" value="<?php $plxShow->comGet('mail',''); ?>" size="30" tabindex="2" aria-required="true" />
			</p>
			<p class="comment-form-url">
				<label for="url"><?php $plxShow->lang('WEBSITE') ?></label>
				<input id="url" name="site" type="text" value="<?php $plxShow->comGet('site',''); ?>" size="30" tabindex="3" />
			</p>
			<p class="comment-form-comment">
				<label for="comment">Comment</label>
				<textarea id="comment" name="content" cols="45" rows="8" tabindex="4" aria-required="true"><?php $plxShow->comGet('content',''); ?></textarea>
			</p>												
			
			<?php if($plxShow->plxMotor->aConf['capcha']): ?>

			<div id="c-apcha">
			<label for="id_rep"><strong><?php echo $plxShow->lang('ANTISPAM_WARNING') ?></strong>&nbsp;:</label>
			<p><?php $plxShow->capchaQ(); ?>&nbsp;:&nbsp;<input id="id_rep" name="rep" type="text" size="10" /></p>
				<input name="rep2" type="hidden" value="<?php $plxShow->capchaR(); ?>" />
			</div>
			<?php endif; ?>

			<p class="form-submit">
				<input name="submit" type="submit" id="submit" tabindex="5" value="<?php $plxShow->lang('SEND') ?>" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $plxShow->artId();?>" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $plxShow->artId();?>" id="comment_post_ID" />
				<input type="hidden" name="comment_parent" id="comment_parent" value="0" />
			</p>
		</form>
	</div><!-- #respond -->

	<?php else: ?>
		<p><?php $plxShow->lang('COMMENTS_CLOSED') ?>.</p>
	<?php endif; # Fin du if sur l'autorisation des commentaires ?>

</div>
<!-- end #comments -->
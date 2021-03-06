<?php if(!defined('PLX_ROOT')) exit; ?>

	<?php if($plxShow->plxMotor->plxRecord_coms): ?>

	<div id="comments">

	<h2>
		<?php echo $plxShow->artNbCom() ?>
	</h2>

		<?php while($plxShow->plxMotor->plxRecord_coms->loop()): # On boucle sur les commentaires ?>

		<div id="<?php $plxShow->comId(); ?>" class="comment">
			<blockquote>
				<p class="info_comment"><a class="num-com" href="<?php $plxShow->ComUrl() ?>" title="#<?php echo $plxShow->plxMotor->plxRecord_coms->i+1 ?>">#<?php echo $plxShow->plxMotor->plxRecord_coms->i+1 ?></a> <?php $plxShow->comDate('#day #num_day #month #num_year(4) &#64; #hour:#minute'); ?> <?php $plxShow->comAuthor('link'); ?> <?php $plxShow->lang('SAID') ?> : </p>
				<p class="content_com type-<?php $plxShow->comType(); ?>"><?php $plxShow->comContent() ?></p>
			</blockquote>
		</div>

		<?php endwhile; # Fin de la boucle sur les commentaires ?>

		<div class="rss">
			<?php $plxShow->comFeed('rss',$plxShow->artId()); ?>
		</div>

	</div>

	<?php endif; ?>

	<?php if($plxShow->plxMotor->plxRecord_arts->f('allow_com') AND $plxShow->plxMotor->aConf['allow_com']): ?>

	<div id="form">

		<h2>
			<?php $plxShow->lang('WRITE_A_COMMENT') ?>
		</h2>

		<form action="<?php $plxShow->artUrl(); ?>#form" method="post">
			<fieldset>
				<p><label for="id_name"><?php $plxShow->lang('NAME') ?>&nbsp;:</label></p>
				<p><input id="id_name" name="name" type="text" size="20" value="<?php $plxShow->comGet('name',''); ?>" maxlength="30" />
				</p>
				<p>
					<label for="id_site"><?php $plxShow->lang('WEBSITE') ?>&nbsp;:</label>
				</p>
				<p>
					<input id="id_site" name="site" type="text" size="20" value="<?php $plxShow->comGet('site',''); ?>" />
				</p>
				<p>
					<label for="id_mail"><?php $plxShow->lang('EMAIL') ?>&nbsp;:</label>
				</p>
				<p>
					<input id="id_mail" name="mail" type="text" size="20" value="<?php $plxShow->comGet('mail',''); ?>" />
				</p>
				<p>
					<label for="id_content" class="lab_com"><?php $plxShow->lang('COMMENT') ?>&nbsp;:</label>
				</p>
				<p>
					<textarea id="id_content" name="content" cols="35" rows="6"><?php $plxShow->comGet('content',''); ?></textarea>
				</p>
				<p class="com-alert">
					<?php $plxShow->comMessage(); ?>
				</p>
				<p>
					<?php if($plxShow->plxMotor->aConf['capcha']): ?>
					<label for="id_rep"><strong><?php echo $plxShow->lang('ANTISPAM_WARNING') ?></strong>&nbsp;:</label>
				</p>
				<p>
					<?php $plxShow->capchaQ(); ?>&nbsp;:&nbsp;<input id="id_rep" name="rep" type="text" size="10" />
					<?php endif; ?>
				</p>
				<p>
					<input type="submit" value="<?php $plxShow->lang('SEND') ?>" />
				</p>
			</fieldset>
		</form>

	</div>

	<?php else: ?>

		<p>
			<?php $plxShow->lang('COMMENTS_CLOSED') ?>.
		</p>

	<?php endif; # Fin du if sur l'autorisation des commentaires ?>

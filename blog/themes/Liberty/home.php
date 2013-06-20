<?php include(dirname(__FILE__).'/header.php');
      include(dirname(__FILE__).'/smarty_modifier_truncate.php');?>

  <?php while($plxShow->plxMotor->plxRecord_arts->loop()):
	ob_start();
	echo $plxShow->plxMotor->plxRecord_arts->f('format');
	$format = ob_get_clean();
	ob_start();
	echo $plxShow->plxMotor->plxRecord_arts->f('nb_com');
	$nb_com = ob_get_clean();
  ?>

			<article class="post format-<?php echo $nb_com != 0 ? 'chat' : (!empty($format) ? $format : 'standard'); ?>">
				<div class="postformat">
					<div class="format-icon"></div>
				</div>
			    <header class="entry-header">
				    <h1 class="entry-title">
				    	<?php $plxShow->artTitle('link'); ?>

				    </h1>
				  </header>
				  <span class="date-i fleft">
				  	<a href="<?php echo $plxShow->plxMotor->urlRewrite('?article'.intval($plxShow->plxMotor->plxRecord_arts->f('numero')).'/'.$plxShow->plxMotor->plxRecord_arts->f('url')); ?>">
				  		<?php $plxShow->artDate('#num_day #month #num_year(4)'); ?>
				  	</a>
				  </span>
				  <div class="entry-content">
					<?php ob_start(); 
		        	$plxShow->artContent($chapo=true);
		        	$content = ob_get_clean();
		        	ob_start();
		        	$plxShow->artUrl();
		        	$link = ob_get_clean();

		        	$masque = '#<img(.+?)src="(.+?)"#i'; preg_match($masque, $content, $resultats);
		        	$extension = pathinfo($resultats[2], PATHINFO_EXTENSION); ?>

		        	<?php if($extension['extension'] == ('jpg'||'jpeg'||'png'||'gif')) : ?>									
		        	<a href="<?php $plxShow->artUrl($type='relatif'); ?>" rel="bookmark" title="Lien vers <?php $plxShow->artTitle(''); ?>">        
		        	<img src="<?php $plxShow->template(); ?>/timthumb.php?src=<?php echo $resultats[2]; ?>&amp;w=100&amp;h=100&amp;zc=1&amp;q=90" alt="<?php $plxShow->artTitle(''); ?>" width="100px" height="100px" class="main-image thumb alignleft border" /></a>
		        	<?php endif; ?>
		        	
		        	<?php $text = preg_replace('/<img(.*?)>/', '', $content, 1);
		        	echo smarty_modifier_truncate($text, 200, $etc = '(...)',false,false,$link); ?>
					
					<footer class="read-more">
						<a href="<?php $plxShow->artUrl($type='relatif'); ?>" rel="bookmark" title="Lien vers <?php $plxShow->artTitle(''); ?>">
							Lire la suite
						</a>
						<span class="meta-nav">&rarr;</span>
					</footer>
				    <div class="clear"></div>
				  </div>
				  <!-- .entry-content -->
			</article>

	<?php endwhile; ?>

	<div class="Nav">
		<?php $plxShow->pagination(); ?>				

	</div>

		<?php include(dirname(__FILE__).'/sidebar.php'); ?>

<?php include(dirname(__FILE__).'/footer.php'); ?>
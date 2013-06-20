<?php include(dirname(__FILE__).'/header.php'); ?>
		<article class="post format-<?php echo $plxShow->plxMotor->mode == 'allarchive' ? 'aside' : ($plxShow->plxMotor->mode == 'contact' ? 'chat' : 'standard');?>">
			<div class="postformat">
				<div class="format-icon"></div>
			</div>
	    	<header class="entry-header">
				<h1 class="entry-title"><?php $plxShow->staticTitle(); ?></h1>
			</header>
			
			<div class="entry-content">
				<?php $plxShow->staticContent(); ?>

	        <!-- .entry-content -->
	    	<div class="clear"></div>
	  		</div>
	  		
		</article>
		
		<?php include(dirname(__FILE__).'/sidebar.php'); ?>

<?php include(dirname(__FILE__).'/footer.php'); ?>

<?php include(dirname(__FILE__).'/header.php');  ?>
		<article class="post format-standard">
			<div class="postformat">
				<div class="format-icon"></div>
			</div>
	    	<header class="entry-header">
				<h1 class="entry-title">
		    		<?php $plxShow->lang('ERROR') ?> :

				</h1>
			</header>
			
			<div class="entry-content">
				<?php $plxShow->erreurMessage(); ?>

	        <!-- .entry-content -->
	    	<div class="clear"></div>
	  		</div>
	  		
		</article>
		
		<?php include(dirname(__FILE__).'/sidebar.php'); ?>

<?php include(dirname(__FILE__).'/footer.php'); ?>

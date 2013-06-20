<?php include(dirname(__FILE__).'/header.php'); ob_start();
	echo $plxShow->plxMotor->plxRecord_arts->f('format');
	$format = ob_get_clean();
  ?>
		<article class="post format-<?php echo !empty($format) ? $format : 'standard'; ?>">
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
				<?php $plxShow->artContent(); ?>

	        <!-- .entry-content -->
	    	<div class="clear"></div>
	  		</div>
	  		<footer class="entry-utility">
   			<div class="alignright">
      			<div class="newer-older">
      				<?php echo $plxShow->callHook('prevNext',array(
      					true,
      					'<a href="#prevUrl" rel="prev"><img src="'.$plxMotor->urlRewrite().'themes/Escape/images/forword.png" alt="&laquo;" title="#prevTitle"></a>'."\n\t\t\t\t\t",
      					'<a href="#nextUrl" rel="next"><img  src="'.$plxMotor->urlRewrite().'themes/Escape/images/next.png" alt="&raquo;" title="#nextTitle"></a>'
      				)); ?>

				</div>
   			</div>
    		<div class="alignleft">
      			<div class="author-i">
					<b>Author:</b> 	
					<span class="vcard"> 
						<?php $plxShow->artAuthor();?>

					</span>
				</div >
      			<div class="category">
					<b> Category:</b> 
						<?php $plxShow->artCat(); ?>

				</div>
      			<div class="tag">
      				<b><?php $plxShow->lang('TAGS') ?> :</b> 
      					<?php $plxShow->artTags(); ?>

      			</div>
		    </div>
    		<div class="clear"></div>
  			</footer>
		</article>
		<?php include(dirname(__FILE__).'/commentaires.php'); ?>
		
		<?php include(dirname(__FILE__).'/sidebar.php'); ?>

<?php include(dirname(__FILE__).'/footer.php'); ?>

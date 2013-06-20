<?php if(!defined('PLX_ROOT')) exit; ?>
<!DOCTYPE html>
<html dir="ltr" lang="<?php $plxShow->defaultLang() ?>">
<head>
	<meta name="viewport" content="width=device-width" />
	<meta charset="<?php $plxShow->charset(); ?>" />
	<title><?php $plxShow->pageTitle(); ?></title>
	<?php $plxShow->meta('description') ?>
	<?php $plxShow->meta('keywords') ?>
	<?php $plxShow->meta('author') ?>

	<link rel="stylesheet" type="text/css" media="all" href="<?php $plxShow->template(); ?>/style.css" />


	<meta name="generator" content="Pluxml <?php echo $plxMotor->aConf['version'] ?>" />
	<style type="text/css">
	body.custom-background { background-color: #ffffff; background-image: url(<?php $plxShow->template(); ?>/images/primary-bg.png); background-repeat: repeat; background-position: top left; background-attachment: scroll; }
	</style>
	<link rel="shortcut icon" href="<?php $plxShow->template() ?>/favicon.png" />
		

	
        
 	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    	<script type="text/javascript">google.load('jquery', '1.4.4');</script>
	<script type="text/javascript" src="plugins/plxzoombox/zoombox/zoombox.js"></script>

    	<script type="text/javascript">
        	//<![CDATA[
        	$(window).scroll(function() {
        	if($(window).scrollTop() == 0){
        		$('#scrollToTop').fadeOut("fast");
        } else {
            if($('#scrollToTop').length == 0){
            $('body').append('<div id="scrollToTop">'+
            '<a href="#"><img src="<?php $plxShow->template(); ?>/images/icons/arrow-up.png"</a>'+
            '</div>');
        }
        $('#scrollToTop').fadeIn("fast");
        }
        });
        $('#scrollToTop a').live('click', function(event){
        event.preventDefault();
        $('html,body').animate({scrollTop: 0}, 'slow');
        });
	//]]>
	</script>

	<script src="<?php $plxShow->template(); ?>/js/javascript.js" type="text/javascript"></script>
<!--[if IE 7]><style type="text/css">
.topbars { padding-top: 12px; }
</style>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php $plxShow->template(); ?>/fancybox/fancybox.css"
media="screen" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="<?php $plxShow->template(); ?>/fancybox/fancybox.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
		// for PDF auto-detection 
$('a[href$=".pdf"]').addClass('fancybox-pdf'); 
		// setup FB for PDF using type iframe 
$('a.fancybox-pdf').fancybox({ 
    	'type'         		 	:'iframe', 
        'titleShow'    			: false, 
        'autoScale'     		:false, 
        'width'         		:'90%', 
        'height'                	:'90%' 
	}); 	
}); 	
</script>

</head>
	
<body class="<?php echo $plxShow->plxMotor->mode; ?> blog custom-background">
	<div id="page">
		<div id="primary">
			<div id="contentcolumn">
                <hgroup id="mobile-version">
                    <h1 class="site-title"><?php $plxShow->mainTitle('link'); ?></h1>
                    <h2 class="site-description"><?php $plxShow->subTitle(); ?></h2>
                </hgroup>

                <div id="header-image">  
					<?php ob_start();
					eval($plxShow->callHook('slideHTML'));
					$slider = ob_get_clean();
					if (empty($slider)) : ?>

					<a href="<?php $plxMotor->urlRewrite();?>"><img src="<?php $plxShow->template(); ?>/images/ny.JPG" alt="Header"/></a>
					<?php else : 
					echo $slider;
					endif;
					?>

				</div>

				<div id="content">
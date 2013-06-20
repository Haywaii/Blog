<?php if(!defined('PLX_ROOT')) exit; ?>
<body>
	<div id="social-icons">
	<div id="social-icons-margins">
		<a href="<?php $plxShow->urlRewrite('feed.php?rss') ?>" onclick="window.open(this.href);return false;" title="<?php $plxShow->lang('ARTICLES_RSS_FEEDS') ?>">
			<img src="<?php $plxShow->template(); ?>/images/icons/rss.png" width="22" height="22" alt="<?php $plxShow->lang('ARTICLES') ?>"/>
		</a>
		<a href="http://www.linkedin.com/profile/view?id=122016541&trk=hb_tab_pro_top" onclick="window.open(this.href);return false;" title="Rejoignez moi sur Linked!n">
			<img src="<?php $plxShow->template(); ?>/images/icons/linkedin.png" width="22" height="22"/>
		</a>
		<a href="http://www.facebook.com/emmanuel.halter.1?ref=tn_tnmn" onclick="window.open(this.href);return false;" title="Retrouvez moi sur facebook">
			<img src="<?php $plxShow->template(); ?>/images/icons/facebook.png" width="22" height="22" alt="<?php $plxShow->lang('COMMENTS') ?>"/>
		</a>
		<a href="http://www.pluxml.org" onclick="window.open(this.href);return false;" title="Pluxml, Generateur de blog et bien plus encore!">
			<img src="<?php $plxShow->template(); ?>/images/icons/pluxml.png" width="22" height="22" alt="Pluxml"/>
		</a>	
		<a class="admin" rel="nofollow" href="<?php $plxShow->urlRewrite('core/admin/') ?>" title="<?php $plxShow->lang('ADMINISTRATION') ?>" onclick="window.open(this.href);return false;">
			<img src="<?php $plxShow->template(); ?>/images/icons/option-icon-general.png" alt="Administration" />
		</a>
	</div><!--/social-icons-margins-->
</div><!--/social-icons-->
<div class="clear"></div>

</div>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js?ver=3.3.2"></script>
	
</body>
</html>

<!--feed.php?rss-->
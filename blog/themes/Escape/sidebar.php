<?php if(!defined('PLX_ROOT')) exit; ?>

  </div><!-- #content -->
<div class="clear"></div></div>
<!--/contentcolumn-->
</div>
<!--/primary-->
<div id="secondary">
<div id="secondary-margins">
  <header id="branding">
    <hgroup id="desktop-version">
    	<h1 class="site-title"><?php $plxShow->mainTitle('link'); ?></h1>
        <h2 class="site-description"><?php $plxShow->subTitle(); ?></h2>
    </hgroup>
  </header>
  <nav>
    <div class="menu">
    	<ul>
			
<!-- <?php $plxShow->catList($plxShow->getLang('HOME'),
array('
<li class="#cat_status page_item">
<a href="#cat_url" title="#cat_name"><span>#cat_name</span>
</a>
</li>'."\n\t\t",15),'','001|002|003|004|005|006'); ?> -->
			
<?php $plxShow->staticList('','
<li id="#static_id">
<a href="#static_url" class="#static_status" title="#static_name">#static_name
</a>
</li>');?> 


			
<?php $plxShow->pageBlog('
<li id="#page_id">
<a class="#page_status" href="#page_url" title="#page_name">#page_name
</a>
</li>'); ?>
			
		</ul>
    </div>
  </nav>
  <div class="widget-area">
  	<aside class="widget">
  	<h3>Versions</h3>
	<div id="versions">
		<?php eval($plxShow->callHook('MySkinSelect')) ?>

	</div>
	</aside>


	<aside id="calendar-3" class="widget widget_calendar"><h3>Calendrier</h3>
		<div id="calendar_wrap">
			<?php eval($plxShow->callHook('CalInSidebar')); ?>
		</div>
	</aside>

	<aside id="linkcat-2" class="widget widget_links"><h3>Friends</h3>
		<ul class='friends'>
			<?php eval($plxShow->callHook('showBlogroll')); ?>
			<li>
				<a href="http://adrian.gaudebert.fr/" onclick="window.open(this.href);return false;" title="Le web d'abord" > Adrian G</a>
			</li>
			<li>
				<a href="http://www.martiusweb.net/#home/" onclick="window.open(this.href);return false;" title="La page web de Martius"> Martius</a>
			</li>
			<li>
				<a href="http://www.titipops.fr/" onclick="window.open(this.href);return false;" title="L'art avant tout"> Lilitha</a>
			</li>
			<li>
				<a href="http://les-gouters-de-safinette.overblog.com/" onclick="window.open(this.href);return false;" title="Id&eacute;es de cuisine"> Safinette</a>
			</li>
			<li>
				<a href="http://axel.royerb.com/blog/" onclick="window.open(this.href);return false;" title="Hardware et Application mobile"> Axel</a>
			</li>
			<li>
				<a href="http://www.carnetdescapades.com/" onclick="window.open(this.href);return false;" title="Sacr&eacute; voyageuse"> Laur&egrave;ne</a>
			</li>

		</ul>
	</aside>
	<aside class="widget adverts"><h3>Coup de coeur</h3>
		<ul>
		<li><a href="http://www.zapiks.fr/" onclick="window.open(this.href);return false;" title="Le sport en vid&eacute;o"><img src="data/images/zapiksicon.jpg" alt="Zapiks"/></a></li>			
		<li><a href="http://www.ted.com/" onclick="window.open(this.href);return false;" title="De bonnes id&eacute;es &agrave; partager"><img src="data/images/tedicon1.jpg" alt="Ted"/></a></li>
		<li><a href="http://www.ondar.fr/" onclick="window.open(this.href);return false;" title="De l'humour rien que &ccedil;a !"><img src="data/images/ondaricon.png" alt="Ondar"/></a></li>
		<li><a href="http://www.39-45.org/portailv2/news/news.php" onclick="window.open(this.href);return false;" title="Tout sur la 2nd Guerre Mondiale"><img src="data/images/ww2icon.png" alt="WWII"/></a></li>
		<li><a href="http://www.inc.com/" onclick="window.open(this.href);return false;" title="Business & News"><img src="data/images/inc.jpg" alt="Inc"/></a></li>
		<li><a href="http://www.lesmachettes.com/" onclick="window.open(this.href);return false;" title="Les femmes d'abord"><img src="data/images/machettes.png" alt="machettes"/></a></li>
		
		</ul>
		<div class="clearfix"></div>
	</aside>
	<aside id="recent-post" class="widget widget_recent_entries">		
			<h3><?php $plxShow->lang('LAST_ARTICLES') ?></h3>
		<ul>
			<?php $plxShow->lastArtList('<li class="#art_status"><a href="#art_url" title="#art_title">#art_title</a></li>'); ?>

		</ul>
	</aside>
	<aside class="widget"><h3><?php $plxShow->lang('Th&egrave;mes ABORD&Eacute;S') ?></h3>
		<ul>
			<?php $plxShow->catList('','<li id="#cat_id" class="#cat_status"><a href="#cat_url" title="#cat_name">#cat_name</a> (#art_nb)</li>'); ?>
		
		</ul>
	</aside>
	<aside class="widget"><h3><?php $plxShow->lang('LAST_COMMENTS') ?></h3>
		<ul>
			<?php $plxShow->lastComList('<li><a href="#com_url">#com_author '.$plxShow->getLang('SAID').' : #com_content(34)</a></li>'); ?>
		
		</ul>
	</aside>
	</div>
</div>
</div><!--/secondary-->

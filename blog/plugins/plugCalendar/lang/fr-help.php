<?php if(!defined('PLX_ROOT')) exit; ?>

<h2>Aide</h2>
<p>Fichier d&#039;aide du plugin plugCalendar</p>

<p>&nbsp;</p>
<h3>Installation</h3>
<p>Pensez &agrave; activer le plugin.<br/>
Editez le fichier template "sidebar.php". Ajoutez y le code suivant &agrave; l&#039;endroit o&ugrave; vous souhaitez voir apparaitre le calendrier:</p>
<pre>&lt;?php eval($plxShow-&gt;callHook(&#039;CalInSidebar&#039;)); ?&gt;</pre>
<p>&nbsp;</p>
<h3>Utilisation</h3>
<p>
	Le plugin ajoute un calendrier à l'endroit souhaité. Par défaut, le calendrier affiche le mois en cours. Si un jour contient un article, un lien vers la page d'archive correspondant à ce jour s'affiche à la place du jour.</p>
<p>Si des articles ont été postés avant le mois en cours, un lien vers le mois qui contient des articles, le plus proche dans le temps du mois en cours, est affiché sous le calendrier. Ce lien dirige vers la page d'archive du mois en question.</p>
<p>Si on clique sur ce lien, le calendrier affiche le mois sélectionné. Des liens sous le calendrier affichent les mois qui contiennent des articles, les plus proches dans le temps du mois sélectionné.</p>

<p>&nbsp;</p>
<h3>Structure HTML</h3>
<p>Exemple de Mai 2012</p>
<pre>
&lt;div class="calendar"&gt;>
				
		&lt;div class="month"&gt;>Mai&lt;/div&gt;>
		&lt;div class="year" id="y_2012"&gt;>2012&lt;/div&gt;>
			&lt;table&gt;>
				&lt;thead&gt;>
					&lt;tr&gt;>
						
							&lt;td&gt;>Lun&lt;/td&gt;>
							
							&lt;td&gt;>Mar&lt;/td&gt;>
							
							&lt;td&gt;>Mer&lt;/td&gt;>
							
							&lt;td&gt;>Jeu&lt;/td&gt;>
							
							&lt;td&gt;>Ven&lt;/td&gt;>
							
							&lt;td&gt;>Sam&lt;/td&gt;>
							
							&lt;td&gt;>Dim&lt;/td&gt;>
													
					&lt;/tr&gt;>
				&lt;/thead&gt;>
				&lt;tbody&gt;>
					&lt;tr&gt;>
						
							&lt;td colspan="1" class="merge"&gt;>&lt;/td&gt;>
							 
							&lt;td &gt;>1&lt;/td&gt;>
							 
							&lt;td &gt;>2&lt;/td&gt;>
							 
							&lt;td &gt;>3&lt;/td&gt;>
							 
							&lt;td &gt;>4&lt;/td&gt;>
							 
							&lt;td &gt;>5&lt;/td&gt;>
							 
							&lt;td  id="today"&gt;>6&lt;/td&gt;>
							
					&lt;/tr&gt;>
					&lt;tr&gt;>
						 
							&lt;td &gt;>7&lt;/td&gt;>
							 
							&lt;td &gt;>8&lt;/td&gt;>
							 
							&lt;td &gt;>9&lt;/td&gt;>
							 
							&lt;td &gt;>10&lt;/td&gt;>
							 
							&lt;td &gt;>11&lt;/td&gt;>
							 
							&lt;td &gt;>12&lt;/td&gt;>
							 
							&lt;td &gt;>13&lt;/td&gt;>
							
					&lt;/tr&gt;>
					&lt;tr&gt;>
						 
							&lt;td &gt;>14&lt;/td&gt;>
							 
							&lt;td &gt;>15&lt;/td&gt;>
							 
							&lt;td &gt;>16&lt;/td&gt;>
							 
							&lt;td &gt;>17&lt;/td&gt;>
							 
							&lt;td &gt;>18&lt;/td&gt;>
							 
							&lt;td &gt;>19&lt;/td&gt;>
							 
							&lt;td &gt;>20&lt;/td&gt;>
							
					&lt;/tr&gt;>
					&lt;tr&gt;>
						 
							&lt;td &gt;>21&lt;/td&gt;>
							 
							&lt;td &gt;>22&lt;/td&gt;>
							 
							&lt;td &gt;>23&lt;/td&gt;>
							 
							&lt;td &gt;>24&lt;/td&gt;>
							 
							&lt;td &gt;>25&lt;/td&gt;>
							 
							&lt;td &gt;>26&lt;/td&gt;>
							 
							&lt;td &gt;>27&lt;/td&gt;>
							
					&lt;/tr&gt;>
					&lt;tr&gt;>
						 
							&lt;td &gt;>28&lt;/td&gt;>
							 
							&lt;td &gt;>29&lt;/td&gt;>
							 
							&lt;td &gt;>30&lt;/td&gt;>
							 
							&lt;td &gt;>31&lt;/td&gt;>
							
							&lt;td colspan="3" class="merge"&gt;>&lt;/td&gt;>
												
					&lt;/tr&gt;>
				&lt;/tbody&gt;>
			&lt;/table&gt;>
			
		&lt;/div&gt;>
		&lt;div id="selectMonth"&gt;>&lt;&lt;&nbsp;&lt;a href=""&gt;>février 2012&lt;/a&gt;>&lt;/div&gt;> //Uniquement si des articles ont été postés en février 2012.
</pre>
<h3>Configuration de la css</h3>
<p>Les différentes classes sont :</p>
<ul>
	<li>calendar : classe de la div encadrant l'ensemble du calendrier sauf les liens vers les mois</li>
	<li>month : classe du mois affiché</li>
	<li>year : classe de l'année affichée</li>
	<li>merge : classe des cases du calendrier dans lesquelles aucun jour n'est affiché. Ces cases sont fusionnées.</li>
</ul>
<p>Les différents index sont :</p>
<ul>
	<li>y_{année affichée} : index de l'année affichée. Par exemple pour 2012, il est égal à y_2012</li>
	<li>today : index du jour courant</li>
	<li>selectMonth : index des liens situés sous le calendrier (mois)</li>
</ul>
<?php 
/**
 * Plugin about
 *
 * @package PLX
 * @version 1.1
 * @date	14/05/2012
 * @author	Cyril MAGUIRE
 **/
if(!defined('PLX_ROOT')) exit; 

$plxMotor = plxMotor::getInstance();

# Control du token du formulaire
plxToken::validateFormToken($_POST);

$plxPlugin->getAbout(PLX_ROOT.$plxPlugin->getParam('about'));

# On édite les catégories
if(!empty($_POST)) {
	$plxPlugin->editAboutlist($_POST);
	header('Location: plugin.php?p=about');
	exit;
}

?>

<h2><?php $plxPlugin->lang('L_TITLE_CONFIG'); ?></h2>
<p><?php $plxPlugin->lang('L_DESCRIPTION_CONFIG'); ?></p>

<form action="plugin.php?p=about" method="post" id="form_about">
	<p>
	<label><?php $plxPlugin->lang('L_INFOS'); ?></label>
	<br/>
	<?php echo plxUtils::printArea('content',plxUtils::strCheck($plxPlugin->aboutList['infos']),150,10); ?>
	</p>
	<table class="table">
	<caption><?php $plxPlugin->lang('L_ADMIN_LIST_PIC')?></caption>
	<thead>
		<tr>
			<th class="checkbox"><input type="checkbox" onclick="checkAll(this.form, 'idAbout[]')" /></th>
			<th class="title"><?php $plxPlugin->lang('L_ADMIN_LIST_ID'); ?></th>
			<th><?php $plxPlugin->lang('L_ADMIN_LIST_THUMB') ?></th>
			<th><?php $plxPlugin->lang('L_ADMIN_LIST_ALT') ?></th>
			<th><?php $plxPlugin->lang('L_ADMIN_LIST_URL') ?></th>
			<th><?php $plxPlugin->lang('L_ADMIN_LIST_DESC') ?></th>
			<th><?php $plxPlugin->lang('L_ADMIN_LIST_EXT_LINK') ?></th>
			<th><?php $plxPlugin->lang('L_ADMIN_LIST_ORDER') ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$num = 0;
		if ($plxPlugin->aboutList) {
			foreach($plxPlugin->aboutList as $k=>$v) {
				if ($k != 'infos') {
				$ordre = ++$num;
				echo '<tr class="line-'.($num%2).'">';
				echo '<td><input type="checkbox" name="idAbout[]" value="'.$k.'" /><input type="hidden" name="aboutNum[]" value="'.$k.'" /></td>';
				echo '<td>'.$plxPlugin->getLang('L_ADMIN_LIST_LINK').' '.$k.'</td>';
				echo '<td><img src="'.(strpos(plxUtils::strCheck($v['url']),'http') === false ? $plxMotor->urlRewrite(plxUtils::strCheck($v['url'])):plxUtils::strCheck($v['url'])).'" alt="'.plxUtils::strCheck($v['alt']).'" width="42" height="42"/></td><td>';
				plxUtils::printInput($k.'_alt', plxUtils::strCheck($v['alt']), 'text', '15-50');
				echo '</td><td>';
				plxUtils::printInput($k.'_url', plxUtils::strCheck($v['url']), 'text', '25-150');
				echo '</td><td>';
				plxUtils::printInput($k.'_description', plxUtils::strCheck($v['description']), 'text', '25-150');
				echo '</td><td>';
				plxUtils::printInput($k.'_extLink', plxUtils::strCheck($v['extLink']), 'text', '25-150');
				echo '</td><td>';
				plxUtils::printInput($k.'_ordre', $ordre, 'text', '3-3');
				echo '</td></tr>';
				}
			}
			# On récupère le dernier identifiant
			$a = array_keys($plxPlugin->aboutList);
			rsort($a);
		}
		else {
			$a[1] = 0;
		}
		$new_aboutid = str_pad($a[1]+1, 3, "0", STR_PAD_LEFT);
		 ?>
		 <tr class="new">
		 		<td>&nbsp;</td>
			
			<?php
				echo '<td><input type="hidden" name="aboutNum[]" value="'.$new_aboutid.'" /></td>';
				echo '<td>'.$plxPlugin->getLang('L_ADMIN_LIST_NEW').'</td><td>';
				plxUtils::printInput($new_aboutid.'_alt', '', 'text', '15-50');
				echo '</td><td>';
				plxUtils::printInput($new_aboutid.'_url', '', 'text', '25-150');
				echo '</td><td>';
				plxUtils::printInput($new_aboutid.'_description', '', 'text', '25-150');
				echo '</td><td>';
				plxUtils::printInput($new_aboutid.'_extLink', '', 'text', '25-150');
				echo '</td><td>';
				plxUtils::printInput($new_aboutid.'_ordre', ++$num, 'text', '3-3');
				echo '</td>';
			?>
			<td>&nbsp;</td>
		</tr>
	</tbody>
	</table>

	<p class="center">
	<?php echo plxToken::getTokenPostMethod() ?>
		<input class="button update " type="submit" name="update" value="<?php $plxPlugin->lang('L_ADMIN_APPLY_BUTTON'); ?>" />
	</p>
	<p>
		<?php plxUtils::printSelect('selection', array( '' => $plxPlugin->getLang('L_ADMIN_SELECTION'), 'delete' => $plxPlugin->getLang('L_ADMIN_DELETE')), '') ?>
		<input class="button submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
	</p>

</form>

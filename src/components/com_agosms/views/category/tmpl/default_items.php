<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.framework');

// Create a shortcut for params.
$params = &$this->item->params;

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on agosms permissinos.
$canEdit = $user->authorise('core.edit', 'com_agosms.category.' . $this->category->id);
$canCreate = $user->authorise('core.create', 'com_agosms');
$canEditState = $user->authorise('core.edit.state', 'com_agosms');

$n = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_AGOSMS_NO_AGOSMS'); ?></p>
<?php else : ?>

<!-- TGE
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) : ?>
	<fieldset class="filters btn-toolbar">
		<?php if ($this->params->get('filter_field') != 'hide') : ?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search"><?php echo JText::_('COM_AGOSMS_FILTER_LABEL') . '&#160;'; ?></label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_AGOSMS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_AGOSMS_FILTER_SEARCH_DESC'); ?>" />
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>
	</fieldset>
	<?php endif; ?>
 -->

<!-- TGE
		<ul class="category list-striped list-condensed">
-->

<?php
  // Some vars
  $idx = 0;
  $jumpToMapImg = JURI::base() . 'media/mod_agosm/leaflet-gpx/pin-icon-start.png';
?>

<div id="pager" class="tge-ts-pager">
  <form>
    <img src="media/mod_agosm/tablesorter/first.png" class="first"/>
    <img src="media/mod_agosm/tablesorter/prev.png" class="prev"/>
    <!-- the "pagedisplay" can be any element, including an input -->
    <span class="pagedisplay" data-pager-output-filtered="{startRow:input} &ndash; {endRow} / {filteredRows} of {totalRows} total rows"></span>
    <img src="media/mod_agosm/tablesorter/next.png" class="next"/>
    <img src="media/mod_agosm/tablesorter/last.png" class="last"/>
    <select class="pagesize">
      <option value="10">10</option>
      <option value="20">20</option>
      <option value="50">50</option>
      <option value="100">100</option>
      <option selected="selected" value="9999">Alle</option>
    </select>
  </form>
</div>

<div style="overflow-x:scroll;">
<table class="tge_agosm_tablesorter tablesorter" id="tge_agosm_tablesorter">

			<?php foreach ($this->items as $i => $item) : ?>
				<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
<?php
  $customFields = FieldsHelper::getFields('com_agosms.agosm', $item, true);

  // We need that one to format the custom fields, namely description
  $dispatcher = JEventDispatcher::getInstance();

  // First item? Generate table header. The two columns Koordinaten and ID will be hidden (above)
  if($idx == 0) :
    echo "<thead>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Beschreibung</th>";
    echo "<th>Koordinaten</th>"; // Hidden
    echo "<th>ID</th>"; // Hidden
    foreach ($customFields as $i) :
      echo "<th> $i->label </th>";
    endforeach;
    echo "<th>Zeige in Karte</th>";
    if ($canEdit) :
      echo "<th>Editiere</th>";
    endif;
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
  endif; 

  // Generate current row
  echo "<tr>";
  $t = $this->escape($item->title);
  echo "<td>$t</td>";

  $d = $item->description;
  if(strpos($d, '{field'))
  {
    // TGE: Pretty ugly, but did not find a better way :-|
    // Save away possibly set item->text, then set that to popuptext. The standard plugins/content/fields/fields.php cares for "text" only
    $tmpText = $item->text ? $item->text : null;
    $item->text = $item->description;
    // This will trigger the standard fields plugin:
    $dispatcher->trigger('onContentPrepare', array('com_agosms.agosm', &$item, &$item->params, 0));
    $d = $item->text;
    if($tmpText) { $item->text = $tmpText; };
  }

  echo "<td>$d</td>";
  echo "<td>$item->coordinates</td>"; // Hidden
  echo "<td>$item->id</td>"; // Hidden
  foreach ($customFields as $i) :
    echo "<td>$i->value</td>";
  endforeach;
  // Have a button that centers the map to this entry
  echo "<td><img alt='Karte' title='Karte' src='$jumpToMapImg' onclick='focusMap($item->coordinates);' width='18' height='18' /></td>";

  if($canEdit) :
   echo '<td><span class="list-edit pull-left width-50">';
   echo JHtml::_('icon.edit', $item, $params); 
   echo "</span></td>";
  endif;

  echo "</tr>";

  // Increment index
  $idx++;

?>

<!--
					<?php if ($this->items[$i]->state == 0) : ?>
						<li class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
					<?php else : ?>
						<li class="cat-list-row<?php echo $i % 2; ?>" >
					<?php endif; ?>
					<?php if ($this->params->get('show_link_hits', 1)) : ?>
						<span class="list-hits badge badge-info pull-right">
							<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>
						</span>
					<?php endif; ?>

					<?php if ($canEdit) : ?>
						<span class="list-edit pull-left width-50">
							<?php echo JHtml::_('icon.edit', $item, $params); ?>
						</span>
					<?php endif; ?>

					<div class="list-title">
						<?php if ($this->params->get('icons', 1) == 0) : ?>
							 <?php echo JText::_('COM_AGOSMS_LINK'); ?>
						<?php elseif ($this->params->get('icons', 1) == 1) : ?>
							<?php if (!$this->params->get('link_icons')) : ?>
								<?php echo JHtml::_('image', 'system/agosm.png', JText::_('COM_AGOSMS_LINK'), null, true); ?>
							<?php else: ?>
								<?php echo '<img src="' . $this->params->get('link_icons') . '" alt="' . JText::_('COM_AGOSMS_LINK') . '" />'; ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php // Compute the correct link ?>
						<?php $menuclass = 'category' . $this->pageclass_sfx; ?>
						<?php $link   = $item->link; ?>
						<?php $width  = $item->params->get('width'); ?>
						<?php $height = $item->params->get('height'); ?>
						<?php if ($width == null || $height == null) : ?>
							<?php $width  = 600; ?>
							<?php $height = 500; ?>
						<?php endif; ?>
						<?php if ($this->items[$i]->state == 0) : ?>
							<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
						<?php endif; ?>

						<?php
						switch ($item->params->get('target', $this->params->get('target')))
						{
							case 1:
								// Open in a new window
								echo '<a href="' . $link . '" target="_blank" class="' . $menuclass . '" rel="nofollow">' .
									$this->escape($item->title) . '</a>';
								break;

							case 2:
								// Open in a popup window
								$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $this->escape($width) . ',height=' . $this->escape($height) . '';
								echo "<a href=\"$link\" onclick=\"window.open(this.href, 'targetWindow', '" . $attribs . "'); return false;\">" .
									$this->escape($item->title) . '</a>';
								break;
							case 3:
								// Open in a modal window
								JHtml::_('behavior.modal', 'a.modal');
								echo '<a class="modal" href="' . $link . '"  rel="{handler: \'iframe\', size: {x:' . $this->escape($width) . ', y:' . $this->escape($height) . '}}">' .
									$this->escape($item->title) . ' </a>';
								break;

							default:
								// Open in parent window
								echo '<a href="' . $link . '" class="' . $menuclass . '" rel="nofollow">' .
									$this->escape($item->title) . ' </a>';
								break;
						}
						?>
						</div>
						<?php $tagsData = $item->tags->getItemTags('com_agosms.agosm', $item->id); ?>
						<?php if ($this->params->get('show_tags', 1)) : ?>
							<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
							<?php echo $this->item->tagLayout->render($tagsData); ?>
						<?php endif; ?>
						<?php if (($this->params->get('show_link_description')) and ($item->description != '')) : ?>
						<?php $images = json_decode($item->images); ?>
						<?php  if (isset($images->image_first) and !empty($images->image_first)) : ?>
						<?php $imgfloat = (empty($images->float_first)) ? $this->params->get('float_first') : $images->float_first; ?>
						<div class="pull-<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'UTF-8'); ?> item-image"> <img
							<?php if ($images->image_first_caption) : ?>
								<?php echo 'class="caption" title="' . htmlspecialchars($images->image_first_caption) . '"'; ?>
							<?php endif; ?>
							src="<?php echo htmlspecialchars($images->image_first); ?>" alt="<?php echo htmlspecialchars($images->image_first_alt); ?>"/> </div>
						<?php endif; ?>
						<?php  if (isset($images->image_second) and !empty($images->image_second)) : ?>
						<?php $imgfloat = (empty($images->float_second)) ? $this->params->get('float_second') : $images->float_second; ?>
						<div class="pull-<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'UTF-8'); ?> item-image"> <img
						<?php if ($images->image_second_caption) : ?>
							<?php echo 'class="caption" title="' . htmlspecialchars($images->image_second_caption) . '"'; ?>
						<?php endif; ?>
						src="<?php echo htmlspecialchars($images->image_second); ?>" alt="<?php echo htmlspecialchars($images->image_second_alt); ?>"/> </div>
						<?php endif; ?>
						<?php echo $item->description; ?>
						<?php endif; ?>
						</li>
-->
				<?php endif;?>
			<?php endforeach; ?>
<!-- TGE
		</ul>
-->
</tbody>
</table>
</div>

<!--
		<?php // Code to add a link to submit a agosm. ?>
		<?php if ($this->params->get('show_pagination')) : ?>
		 <div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
	</form>
-->
<?php endif; ?>


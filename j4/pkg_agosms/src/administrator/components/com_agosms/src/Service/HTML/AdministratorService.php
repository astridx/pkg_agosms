<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Administrator\Service\HTML;

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

/**
 * Agosm HTML class.
 *
 * @since  __BUMP_VERSION__
 */
class AdministratorService
{
	/**
	 * Get the associated language flags
	 *
	 * @param   integer  $agosmid  The item id to search associations
	 *
	 * @return  string  The language HTML
	 *
	 * @throws  Exception
	 */
	public function association($agosmid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = Associations::getAssociations('com_agosms', '#__agosms_details', 'com_agosms.item', $agosmid, 'id', null)) {
			foreach ($associations as $tag => $associated) {
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated agosm items
			$db = Factory::getDbo();
			$query = $db->getQuery(true)
				->select('c.id, c.name as title')
				->select('l.sef as lang_sef, lang_code')
				->from('#__agosms_details as c')
				->select('cat.title as category_title')
				->join('LEFT', '#__categories as cat ON cat.id=c.catid')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->where('c.id != ' . $agosmid)
				->join('LEFT', '#__languages as l ON c.language=l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try {
				$items = $db->loadObjectList('id');
			} catch (\RuntimeException $e) {
				throw new \Exception($e->getMessage(), 500, $e);
			}

			if ($items) {
				foreach ($items as &$item) {
					$text = strtoupper($item->lang_sef);
					$url = Route::_('index.php?option=com_agosms&task=agosm.edit&id=' . (int) $item->id);
					$tooltip = '<strong>' . htmlspecialchars($item->language_title, ENT_QUOTES, 'UTF-8') . '</strong><br>'
						. htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') . '<br>' . Text::sprintf('JCATEGORY_SPRINTF', $item->category_title);
					$classes = 'badge bg-secondary';

					$item->link = '<a href="' . $url . '" title="' . $item->language_title . '" class="' . $classes . '">' . $text . '</a>'
						. '<div role="tooltip" id="tip' . (int) $item->id . '">' . $tooltip . '</div>';
				}
			}

			$html = LayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
	/**
	 * Show the featured/not-featured icon.
	 *
	 * @param   integer  $value      The featured value.
	 * @param   integer  $i          Id of the item.
	 * @param   boolean  $canChange  Whether the value can be changed or not.
	 *
	 * @return  string	The anchor tag to toggle featured/unfeatured agosms.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function featured($value, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states = [
			0 => ['unfeatured', 'agosms.featured', 'COM_CONTACT_UNFEATURED', 'JGLOBAL_ITEM_FEATURE'],
			1 => ['featured', 'agosms.unfeatured', 'JFEATURED', 'JGLOBAL_ITEM_UNFEATURE'],
		];
		$state = ArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon = $state[0] === 'featured' ? 'star featured' : 'star';

		if ($canChange) {
			$html = '<a href="#" onclick="return Joomla.listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="tbody-icon'
				. ($value == 1 ? ' active' : '') . '" aria-labelledby="cb' . $i . '-desc">'
				. '<span class="fas fa-' . $icon . '" aria-hidden="true"></span></a>'
				. '<div role="tooltip" id="cb' . $i . '-desc">' . Text::_($state[3]);
		} else {
			$html = '<a class="tbody-icon disabled' . ($value == 1 ? ' active' : '')
				. '" title="' . Text::_($state[2]) . '"><span class="fas fa-' . $icon . '" aria-hidden="true"></span></a>';
		}

		return $html;
	}
}

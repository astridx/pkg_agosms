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

use Joomla\Utilities\ArrayHelper;

JLoader::register('AgosmsHelper', JPATH_ADMINISTRATOR . '/components/com_agosms/helpers/agosms.php');

/**
 * Agosm HTML helper class.
 *
 * @since  __DELPOY_VERSION__
 */
abstract class JHtmlAgosm
{
	/**
	 * Get the associated language flags
	 *
	 * @param   integer  $agosmid  The item id to search associations
	 *
	 * @return  string  The language HTML
	 *
	 * @throws  Exception
	 *
	 * @since   1.0.40
	 */
	public static function association($agosmid)
	{
		// Defaults
		$html = '';
		$associations = JLanguageAssociations::getAssociations('com_agosms', '#__agosms', 'com_agosms.item', $agosmid);

		// Get the associations
		if ($associations)
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated agosms items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('c.id, c.title as title')
				->select('l.sef as lang_sef, lang_code')
				->from('#__agosms as c')
				->select('cat.title as category_title')
				->join('LEFT', '#__categories as cat ON cat.id=c.catid')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->join('LEFT', '#__languages as l ON c.language=l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (RuntimeException $e)
			{
				throw new Exception($e->getMessage(), 500, $e);
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url  = JRoute::_('index.php?option=com_agosms&task=agosm.edit&id=' . (int) $item->id);

					$tooltip = htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') . '<br />' . JText::sprintf('JCATEGORY_SPRINTF', $item->category_title);
					$classes = 'hasPopover label label-association label-' . $item->lang_sef;

					$item->link = '<a href="' . $url . '" title="' . $item->language_title . '" class="' . $classes
						. '" data-content="' . $tooltip . '" data-placement="top">'
						. $text . '</a>';
				}
			}

			JHtml::_('bootstrap.popover');

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
}

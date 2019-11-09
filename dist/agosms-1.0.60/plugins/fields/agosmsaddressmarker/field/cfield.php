<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

/**
 * Form Field to load a list of custom fields
 *
 * @since  1.0.40
 */
class JFormFieldCfield extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	public $type = 'cfield';

	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since  1.0.40
	 */
	protected static $options = array();

	/**
	 * Method to get the options to populate list
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0.40
	 */
	protected function getOptions()
	{
		// Accepted modifiers
		$hash = md5($this->element);

		$app = JFactory::getApplication();
		$context = $app->input->getCmd('context');

		if (!isset(static::$options[$hash]))
		{
			static::$options[$hash] = parent::getOptions();

			$db = Factory::getDbo();

			// Construct the query
			$query = $db->getQuery(true)
				->select('cf.id AS value, cf.name AS text')
				->from('#__fields AS cf')
				->where('cf.state IN (1)')
				->where('cf.context IN ("' . $context . '")')
				->where('cf.type NOT IN ("agosmsaddressmarker")')
				->group('cf.id, cf.name')
				->order('cf.name');

			// Setup the query
			$db->setQuery($query);

			// Return the result
			if ($options = $db->loadObjectList())
			{
				static::$options[$hash] = array_merge(static::$options[$hash], $options);
			}
		}

		return static::$options[$hash];
	}
}

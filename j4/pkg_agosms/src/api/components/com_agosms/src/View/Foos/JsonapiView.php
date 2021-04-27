<?php
/**
 * @package     Joomla.API
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Api\View\Agosms;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

/**
 * The agosms view
 *
 * @since  __BUMP_VERSION__
 */
class JsonapiView extends BaseApiView
{
	/**
	 * The fields to render item in the documents
	 *
	 * @var  array
	 * @since  __BUMP_VERSION__
	 */
	protected $fieldsToRenderItem = ['id', 'alias', 'name', 'catid'];

	/**
	 * The fields to render items in the documents
	 *
	 * @var  array
	 * @since  __BUMP_VERSION__
	 */
	protected $fieldsToRenderList = ['id', 'alias', 'name', 'catid'];

	/**
	 * Execute and display a template script.
	 *
	 * @param   array|null  $items  Array of items
	 *
	 * @return  string
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function displayList(array $items = null)
	{
		foreach (FieldsHelper::getFields('com_agosms.agosm') as $field)
		{
			$this->fieldsToRenderList[] = $field->id;
		}

		return parent::displayList();
	}

	/**
	 * Execute and display a template script.
	 *
	 * @param   object  $item  Item
	 *
	 * @return  string
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function displayItem($item = null)
	{
		foreach (FieldsHelper::getFields('com_agosms.agosm') as $field)
		{
			$this->fieldsToRenderItem[] = $field->name;
		}

		return parent::displayItem();
	}

	/**
	 * Prepare item before render.
	 *
	 * @param   object  $item  The model item
	 *
	 * @return  object
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function prepareItem($item)
	{
		foreach (FieldsHelper::getFields('com_agosms.agosm', $item, true) as $field)
		{
			$item->{$field->name} = isset($field->apivalue) ? $field->apivalue : $field->rawvalue;
		}

		return parent::prepareItem($item);
	}
}

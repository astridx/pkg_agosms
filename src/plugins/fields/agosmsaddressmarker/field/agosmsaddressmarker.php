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

JFormHelper::loadFieldClass('text');

/**
 * Provides a mechanism for calculating geographic coordinates
 *
 * @since  1.0.40
 */
class JFormFieldAgosmsaddressmarker extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $type = 'Agosmsaddressmarker';

	/**
	 * Layout to render
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $layout = 'agosmsaddressmarker';

	/**
	 * The name of the mapheigth field.
	 *
	 * @var    integer
	 * @since  1.0.40
	 */
	protected $mapheight;

	/**
	 * The name of the maptype field.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $maptype;

	/**
	 * The name of the geocoder field.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $geocoder;

	/**
	 * The name of the googlekey field.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $googlekey;

	/**
	 * The name of the mapboxkey field.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $mapboxkey;

	/**
	 * The name of the addressfields field.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $addressfields;

	/**
	 * The name of the scrollwheelzoom field.
	 *
	 * @var    string
	 * @since  1.0.42
	 */
	protected $scrollwheelzoom;

	/**
	 * The name of the owngooglegesturetext field.
	 *
	 * @var    string
	 * @since  1.0.43
	 */
	protected $owngooglegesturetext;

	/**
	 * The name of the popup field.
	 *
	 * @var    string
	 * @since  1.0.46
	 */
	protected $popup;

	/**
	 * The name of the specialicon field.
	 *
	 * @var    string
	 * @since  1.0.46
	 */
	protected $specialicon;

	/**
	 * The name of the addprivacybox field.
	 *
	 * @var    boolean
	 * @since  1.0.61
	 */
	protected $addprivacybox;

	/**
	 * The name of the showrouting field.
	 *
	 * @var    string
	 * @since  1.0.46
	 */
	protected $showroutingcontrol;

	protected $latmin;
	protected $latmax;
	protected $lonmin;
	protected $lonmax;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to get the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   1.0.40
	 */
	public function __get($name)
	{
		switch ($name) {
			case 'latmin':
			case 'latmax':
			case 'lonmin':
			case 'lonmax':
			case 'mapheight':
			case 'maptype':
			case 'geocoder':
			case 'googlekey':
			case 'mapboxkey':
			case 'owngooglegesturetext':
			case 'specialicon':
			case 'addprivacybox':
			case 'popup':
			case 'showroutingcontrol':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   1.0.40
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true) {
			$this->latmax = (int) $this->element['latmax'];
			$this->latmin = (int) $this->element['latmin'];
			$this->lonmax = (int) $this->element['lonmax'];
			$this->lonmin = (int) $this->element['lonmin'];
			$this->mapheight = (int) $this->element['mapheight'];
			$this->maptype = (string) $this->element['maptype'];
			$this->geocoder = (string) $this->element['geocoder'];
			$this->googlekey = (string) $this->element['googlekey'];
			$this->mapboxkey = (string) $this->element['mapboxkey'];
			$this->addressfields = (string) $this->element['addressfields'];
			$this->scrollwheelzoom = (string) $this->element['scrollwheelzoom'];
			$this->owngooglegesturetext = (string) $this->element['owngooglegesturetext'];
			$this->specialicon = (string) $this->element['specialicon'];
			$this->addprivacybox = (string) $this->element['addprivacybox'];
			$this->popup = (string) $this->element['popup'];
			$this->showroutingcontrol = (string) $this->element['showroutingcontrol'];
		}

		return $result;
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to set the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   1.0.40
	 */
	public function __set($name, $value)
	{
		switch ($name) {
			case 'mapheight':
				$this->mapheight = (int) $value;
				break;

			case 'lonmin':
				$this->lonmin = (int) $value;
				break;


			case 'lonmax':
				$this->lonmax = (int) $value;
				break;


			case 'latmin':
				$this->latmin = (int) $value;
				break;

			case 'latmax':
				$this->latmax = (int) $value;
				break;

			case 'maptype':
				$this->mapheight = (string) $value;
				break;

			case 'geocoder':
				$this->geocoder = (string) $value;
				break;

			case 'googlekey':
				$this->googlekey = (string) $value;
				break;

			case 'mapboxkey':
				$this->mapboxkey = (string) $value;
				break;

			case 'addressfields':
				$this->addressfields = (string) $value;
				break;

			case 'scrollwheelzoom':
				$this->scrollwheelzoom = (string) $value;
				break;

			case 'owngooglegesturetext':
				$this->owngooglegesturetext = (string) $value;
				break;

			case 'specialicon':
				$this->specialicon = (string) $value;
				break;

			case 'addprivacybox':
				$this->addprivacybox = (string) $value;
				break;

			case 'popup':
				$this->popup = (string) $value;
				break;

			case 'showroutingcontrol':
				$this->showroutingcontrol = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Get the layout paths
	 *
	 * @return  array
	 *
	 * @since   1.0.40
	 */
	protected function getLayoutPaths()
	{
		$template = JFactory::getApplication()->getTemplate();

		return [
			JPATH_ADMINISTRATOR . '/templates/' . $template . '/html/layouts/plugins/fields/agosmsaddressmarker',
			dirname(__DIR__) . '/layouts',
			JPATH_SITE . '/layouts'
		];
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.40
	 */
	protected function getInput()
	{

		return $this->getRenderer($this->layout)->render($this->getLayoutData());
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 *
	 * @since 1.0.40
	 */
	protected function getLayoutData()
	{
		$data = parent::getLayoutData();

		// Get the addressfields

		$options = (array) $this->getOptions();

		$extraData = [
			'latmin' => $this->latmin,
			'latmax' => $this->latmax,
			'lonmin' => $this->lonmin,
			'lonmax' => $this->lonmax,
			'mapheight' => $this->mapheight,
			'maptype' => $this->maptype,
			'geocoder' => $this->geocoder,
			'googlekey' => $this->googlekey,
			'mapboxkey' => $this->mapboxkey,
			'addressfields' => $this->addressfields,
			'scrollwheelzoom' => $this->scrollwheelzoom,
			'owngooglegesturetext' => $this->owngooglegesturetext,
			'addprivacybox' => $this->addprivacybox,
			'specialicon' => $this->specialicon,
			'popup' => $this->popup,
			'showroutingcontrol' => $this->showroutingcontrol,
			'options' => $options,
		];

		return array_merge($data, $extraData);
	}
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0.40
	 */
	protected function getOptions()
	{
		$fieldname = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);
		$options   = [];

		foreach ($this->element->xpath('option') as $option) {
			// Filter requirements
			if ($requires = explode(',', (string) $option['requires'])) {
				// Requires multilanguage
				if (in_array('multilanguage', $requires) && !JLanguageMultilang::isEnabled()) {
					continue;
				}

				// Requires associations
				if (in_array('associations', $requires) && !JLanguageAssociations::isEnabled()) {
					continue;
				}

				// Requires adminlanguage
				if (in_array('adminlanguage', $requires) && !JModuleHelper::isAdminMultilang()) {
					continue;
				}

				// Requires vote plugin
				if (in_array('vote', $requires) && !JPluginHelper::isEnabled('content', 'vote')) {
					continue;
				}
			}

			$value = (string) $option['value'];
			$text  = trim((string) $option) != '' ? trim((string) $option) : $value;

			$disabled = (string) $option['disabled'];
			$disabled = ($disabled == 'true' || $disabled == 'disabled' || $disabled == '1');
			$disabled = $disabled || ($this->readonly && $value != $this->value);

			$checked = (string) $option['checked'];
			$checked = ($checked == 'true' || $checked == 'checked' || $checked == '1');

			$selected = (string) $option['selected'];
			$selected = ($selected == 'true' || $selected == 'selected' || $selected == '1');

			$tmp = [
					'value'    => $value,
					'text'     => JText::alt($text, $fieldname),
					'disable'  => $disabled,
					'class'    => (string) $option['class'],
					'selected' => ($checked || $selected),
					'checked'  => ($checked || $selected),
			];

			// Set some event handler attributes. But really, should be using unobtrusive js.
			$tmp['onclick']  = (string) $option['onclick'];
			$tmp['onchange'] = (string) $option['onchange'];

			if ((string) $option['showon']) {
				$tmp['optionattr'] = " data-showon='" .
					json_encode(
						JFormHelper::parseShowOnConditions((string) $option['showon'], $this->formControl, $this->group)
					)
					. "'";
			}

			// Add the option object to the result set.
			$options[] = (object) $tmp;
		}

		if ($this->element['useglobal']) {
			$tmp        = new stdClass;
			$tmp->value = '';
			$tmp->text  = JText::_('JGLOBAL_USE_GLOBAL');
			$component  = JFactory::getApplication()->input->getCmd('option');

			// Get correct component for menu items
			if ($component == 'com_menus') {
				$link      = $this->form->getData()->get('link');
				$uri       = new JUri($link);
				$component = $uri->getVar('option', 'com_menus');
			}

			$params = JComponentHelper::getParams($component);
			$value  = $params->get($this->fieldname);

			// Try with global configuration
			if (is_null($value)) {
				$value = JFactory::getConfig()->get($this->fieldname);
			}

			// Try with menu configuration
			if (is_null($value) && JFactory::getApplication()->input->getCmd('option') == 'com_menus') {
				$value = JComponentHelper::getParams('com_menus')->get($this->fieldname);
			}

			if (!is_null($value)) {
				$value = (string) $value;

				foreach ($options as $option) {
					if ($option->value === $value) {
						$value = $option->text;

						break;
					}
				}

				$tmp->text = JText::sprintf('JGLOBAL_USE_GLOBAL_VALUE', $value);
			}

			array_unshift($options, $tmp);
		}

		reset($options);

		return $options;
	}

	/**
	 * Method to add an option to the list field.
	 *
	 * @param   string  $text        Text/Language variable of the option.
	 * @param   array   $attributes  Array of attributes ('name' => 'value' format)
	 *
	 * @return  JFormFieldList  For chaining.
	 *
	 * @since   1.0.40
	 */
	public function addOption($text, $attributes = [])
	{
		if ($text && $this->element instanceof SimpleXMLElement) {
			$child = $this->element->addChild('option', $text);

			foreach ($attributes as $name => $value) {
				$child->addAttribute($name, $value);
			}
		}

		return $this;
	}
}

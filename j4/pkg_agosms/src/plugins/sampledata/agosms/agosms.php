<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Sampledata.Agosms
 *
 * @copyright   (C) astrid-guenther.de>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Extension\ExtensionHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;
use Joomla\Database\ParameterType;

/**
 * Sampledata - Agosms Plugin
 *
 * @since  __BUMP_VERSION__
 */
class PlgSampledataAgosms extends CMSPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $db;

	/**
	 * Application object
	 *
	 * @var    JApplicationCms
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $app;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $autoloadLanguage = true;

	/**
	 * Holds the data
	 *
	 * @var    string
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $stepsData;

	/**
	 * Get an overview of the proposed sampledata.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onSampledataGetOverview()
	{
		if (!Factory::getUser()->authorise('core.create', 'com_agosms')) {
			return;
		}

		$data = new stdClass;
		$data->name = $this->_name;
		$data->title = Text::_('PLG_SAMPLEDATA_AGOSMS_OVERVIEW_TITLE');
		$data->description = Text::_('PLG_SAMPLEDATA_AGOSMS_OVERVIEW_DESC');
		$data->icon = 'star';
		$data->steps = 4;

		return $data;
	}

	/**
	 * First step to enter the sampledata. Category and Items.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep1()
	{
		$user = Factory::getUser();
		$categoryModel = $this->app->bootComponent('com_categories')
			->getMVCFactory()->createModel('Category', 'Administrator');

		// START BIOTOP_LRT
		$category_Biotop_LRT = [
			'title' => 'Biotop_LRT',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'biotop_lrt',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/parks.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_BIOTOP_LRT_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Biotop_LRT)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_biotop_lrt = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_biotop_lrt,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.840507,14.70654';
					$item['name'] = 'Trockenmauern';
					$item['alias'] = 'trockenmauern';
					$item['popuptext'] = '<p>Trockenmauern sind Mauern, die meist älter sind und aus Naturstein aufgebaut sind. Häufig sind sie nicht verfugt, weshalb sie verschiedenen Pflanzen und Tieren einen Lebensraum bieten. Besondere Pflanzenarten dieses Lebensraums sind der Mauer-Streifenfarn, der Braunstielige Streifenfarn, Scharfer Mauerpfeffer oder die Weiße Fetthenne. Auch verschiedene Flechten und Moose finden hier ein geeignetes Habitat.</p><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_trockenmauer.jpg" target="_blank" title="Trockenmauern">Trockenmauern</a>';
					break;
				case 2:
					$item['coordinates'] = '14.693837,50.84585899999999';
					$item['name'] = 'Bergheide, Felsbandheide';
					$item['alias'] = 'bergheide_felsbandheide';
					$item['popuptext'] = 'Die Berg- und Felsheide ist ein Untertyp der Zwergstrauchheiden. Sie werden von Heidekrautgewächsen und Beerkrautheiden dominiert und kommen auf sauren Silikatstandorten vor.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_felsbandheide.jpg" target="_blank" title="Bergheide, Felsbandheide">Bergheide, Felsbandheide</a>';
					break;
			}
		
			try {
				if (!$agosmsModel->save($item)) {
					throw new Exception($agosmsModel->getError());
				}
			} catch (Exception $e) {
				$response = new stdClass;
				$response->success = false;
				$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());
			
				return $response;
			}
		}

		$this->app->setUserState('sampledata.agosms.items.catId_biotop_lrt', $catId_biotop_lrt);
		// ENDE BIOTOP_LRT





		// START BILDER_LB
		$category_Bilder_LB = [
			'title' => 'Bilder_LB',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'bilder_lb',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_BILDER_LB_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Bilder_LB)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_bilder_lb = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_bilder_lb,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '14.74360525,50.83429623';
					$item['name'] = 'Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein';
					$item['alias'] = 'naturnaher_basenarmer_silikatfels_-_osensteine_mit_kelchstein';
					$item['popuptext'] = '<p>Die Rosensteine sind eine markante Felsgruppe im Zittauer Gebirge, die aus Sandstein aufgebaut ist. Besonders auffällig ist der Kelchstein. Im unteren Bereich ist der Kelchstein sehr schmal, was auf die unterschiedliche Härte verschieden alter Sandsteinschichten zurückzuführen ist.<br></br><a href="http://naturparkblicke.de/images/Bilder/allg_geolog_boeden_rosensteine_kelchstein.jpg" target="_blank" title="Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein">Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein</a>';
					break;
				case 2:
					$item['coordinates'] = '14.69983,50.853134';
					$item['name'] = 'Gesteinstafel Jonsdorf';
					$item['alias'] = 'gesteinstafel_jonsdorf';
					$item['popuptext'] = 'Wer einen Überblick über die Geologie des Zittauer Gebirges erhalten möchte, sollte sich die Gesteinstafel in Jonsdorf näher anschauen. Auf ihr werden anschaulich die unterschiedlich aufgebauten Gesteine dargestellt.<br></br><a href="http://www.naturparkblicke.de/images/Bilder/allg_sonstiges_gesteinstafel_jonsdorf.jpg" target="_blank" title="Gesteinstafel Jonsdorf">Gesteinstafel Jonsdorf</a>';
					break;
			}
		
			try {
				if (!$agosmsModel->save($item)) {
					throw new Exception($agosmsModel->getError());
				}
			} catch (Exception $e) {
				$response = new stdClass;
				$response->success = false;
				$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());
			
				return $response;
			}
		}

		$this->app->setUserState('sampledata.agosms.items.catId_bilder_lb', $catId_bilder_lb);
		// ENDE BILDER_LB



		
		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGOSMS_STEP1_SUCCESS');

		return $response;
	}

	/**
	 * Second step to enter the sampledata. Menus.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep2()
	{
		// Detect language to be used.
		$language   = Multilanguage::isEnabled() ? $this->app->getLanguage()->getTag() : '*';
		$langSuffix = ($language !== '*') ? ' (' . $language . ')' : '';

		// Create the menu types.
		$menuTable = new \Joomla\Component\Menus\Administrator\Table\MenuTypeTable($this->db);

		// Get MenuItemModel.
		$this->menuItemModel = $this->app->bootComponent('com_menus')->getMVCFactory()
			->createModel('Item', 'Administrator', ['ignore_request' => true]);

		// Get previously entered categories id
		$catId = $this->app->getUserState('sampledata.agosms.items.catId_biotop_lrt');

		// Insert menuitem
		$menuItems = [
			[
				'menutype' => 'mainmenu',
				'title' => Text::_('PLG_SAMPLEDATA_AGOSMS_SAMPLEDATA_MENUS_TITLE'),
				'link' => 'index.php?option=com_agosms&view=category&id=' . $catId,
				'component_id' => ExtensionHelper::getExtensionRecord('com_agosms', 'component')->extension_id,
				'params' => [],
			],
		];

		try {
			$menuIdsLevel1 = $this->addMenuItems($menuItems, 1);
		} catch (Exception $e) {
			$response = [];
			$response['success'] = false;
			$response['message'] = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 2, $e->getMessage());

			return $response;
		}

		$response = [];
		$response['success'] = true;
		$response['message'] = Text::_('PLG_SAMPLEDATA_AGOSMS_STEP2_SUCCESS');

		return $response;
	}

	/**
	 * Third step to enter the sampledata. Modules.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep3()
	{
		$app = Factory::getApplication();

		if (!ComponentHelper::isEnabled('com_modules') || !Factory::getUser()->authorise('core.create', 'com_modules')) {
			$response = [];
			$response['success'] = true;
			$response['message'] = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_SKIPPED', 3, 'com_modules');

			return $response;
		}

		// Detect language to be used.
		$language   = Multilanguage::isEnabled() ? Factory::getLanguage()->getTag() : '*';
		$langSuffix = ($language !== '*') ? ' (' . $language . ')' : '';

		// Add Include Paths.
		$model  = new \Joomla\Component\Modules\Administrator\Model\ModuleModel;
		$access = (int) $this->app->get('access', 1);

		$catid = $this->app->getUserState('sampledata.agosms.items.catId_biotop_lrt');

		$modules = [
			[
				'title'     => Text::_('PLG_SAMPLEDATA_AGOSMS_SAMPLEDATA_MODULE_TITLE'),
				'ordering'  => 1,
				'position'  => 'sidebar-right',
				'module'    => 'mod_agosms',
				'showtitle' => 0,
				'params'    => [
					'catid' =>[$catid],
				],
			],
		];

		foreach ($modules as $module) {
			// Append language suffix to title.
			$module['title'] .= $langSuffix;

			// Set values which are always the same.
			$module['id']         = 0;
			$module['asset_id']   = 0;
			$module['language']   = $language;
			$module['note']       = '';
			$module['published']  = 1;


			if (!isset($module['assignment'])) {
				$module['assignment'] = 0;
			} else {
				// Assignment means always "only on the homepage".
				if (Multilanguage::isEnabled()) {
					$homes = Multilanguage::getSiteHomePages();

					if (isset($homes[$language])) {
						$home = $homes[$language]->id;
					}
				}

				if (!isset($home)) {
					$home = $app->getMenu('site')->getDefault()->id;
				}

				$module['assigned'] = [$home];
			}

			if (!isset($module['content'])) {
				$module['content'] = '';
			}

			if (!isset($module['access'])) {
				$module['access'] = $access;
			}

			if (!isset($module['showtitle'])) {
				$module['showtitle'] = 1;
			}

			if (!isset($module['client_id'])) {
				$module['client_id'] = 0;
			}

			if (!$model->save($module)) {
				Factory::getLanguage()->load('com_modules');
				$response = [];
				$response['success'] = false;
				$response['message'] = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 3, Text::_($model->getError()));

				return $response;
			}
		}

		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGOSMS_STEP3_SUCCESS');

		return $response;
	}

	/**
	 * Final step to show completion of sampledata.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep4()
	{
		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGOSMS_STEP4_SUCCESS');

		return $response;
	}

	/**
	 * Adds menuitems.
	 *
	 * @param   array    $menuItems  Array holding the menuitems arrays.
	 * @param   integer  $level      Level in the category tree.
	 *
	 * @return  array  IDs of the inserted menuitems.
	 *
	 * @since  3.8.0
	 *
	 * @throws  Exception
	 */
	private function addMenuItems(array $menuItems, $level)
	{
		$itemIds = [];
		$access  = (int) $this->app->get('access', 1);
		$user    = Factory::getUser();
		$app     = Factory::getApplication();

		// Detect language to be used.
		$language   = Multilanguage::isEnabled() ? Factory::getLanguage()->getTag() : '*';
		$langSuffix = ($language !== '*') ? ' (' . $language . ')' : '';

		foreach ($menuItems as $menuItem) {
			// Reset item.id in model state.
			$this->menuItemModel->setState('item.id', 0);

			// Set values which are always the same.
			$menuItem['id']              = 0;
			$menuItem['created_user_id'] = $user->id;
			$menuItem['alias']           = ApplicationHelper::stringURLSafe($menuItem['title']);

			// Set unicodeslugs if alias is empty
			if (trim(str_replace('-', '', $menuItem['alias']) == '')) {
				$unicode = $app->set('unicodeslugs', 1);
				$menuItem['alias'] = ApplicationHelper::stringURLSafe($menuItem['title']);
				$app->set('unicodeslugs', $unicode);
			}

			// Append language suffix to title.
			$menuItem['title'] .= $langSuffix;

			$menuItem['published']       = 1;
			$menuItem['language']        = $language;
			$menuItem['note']            = '';
			$menuItem['img']             = '';
			$menuItem['associations']    = [];
			$menuItem['client_id']       = 0;
			$menuItem['level']           = $level;
			$menuItem['home']            = 0;

			// Set browserNav to default if not set
			if (!isset($menuItem['browserNav'])) {
				$menuItem['browserNav'] = 0;
			}

			// Set access to default if not set
			if (!isset($menuItem['access'])) {
				$menuItem['access'] = $access;
			}

			// Set type to 'component' if not set
			if (!isset($menuItem['type'])) {
				$menuItem['type'] = 'component';
			}

			// Set template_style_id to global if not set
			if (!isset($menuItem['template_style_id'])) {
				$menuItem['template_style_id'] = 0;
			}

			// Set parent_id to root (1) if not set
			if (!isset($menuItem['parent_id'])) {
				$menuItem['parent_id'] = 1;
			}

			if (!$this->menuItemModel->save($menuItem)) {
				// Try two times with another alias (-1 and -2).
				$menuItem['alias'] .= '-1';

				if (!$this->menuItemModel->save($menuItem)) {
					$menuItem['alias'] = substr_replace($menuItem['alias'], '2', -1);

					if (!$this->menuItemModel->save($menuItem)) {
						throw new Exception($menuItem['title'] . ' => ' . $menuItem['alias'] . ' : ' . $this->menuItemModel->getError());
					}
				}
			}

			// Get ID from menuitem we just added
			$itemIds[] = $this->menuItemModel->getstate('item.id');
		}

		return $itemIds;
	}
}

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
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/parks.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/parks.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
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
					$item['coordinates'] = '50.84585899999999,14.693837';
					$item['name'] = 'Bergheide, Felsbandheide';
					$item['alias'] = 'bergheide_felsbandheide';
					$item['popuptext'] = 'Die Berg- und Felsheide ist ein Untertyp der Zwergstrauchheiden. Sie werden von Heidekrautgewächsen und Beerkrautheiden dominiert und kommen auf sauren Silikatstandorten vor.<a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_felsbandheide.jpg" target="_blank" title="Bergheide, Felsbandheide">Bergheide, Felsbandheide</a>';
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

		for ($i = 1; $i <= 8; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_bilder_lb,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/camera.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/camera.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.83429623,14.74360525';
					$item['name'] = 'Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein';
					$item['alias'] = 'naturnaher_basenarmer_silikatfels_osensteine_mit_kelchstein';
					$item['popuptext'] = '<p>Die Rosensteine sind eine markante Felsgruppe im Zittauer Gebirge, die aus Sandstein aufgebaut ist. Besonders auffällig ist der Kelchstein. Im unteren Bereich ist der Kelchstein sehr schmal, was auf die unterschiedliche Härte verschieden alter Sandsteinschichten zurückzuführen ist.<a href="http://naturparkblicke.de/images/Bilder/allg_geolog_boeden_rosensteine_kelchstein.jpg" target="_blank" title="Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein">Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein</a>';
					break;
				case 2:
					$item['coordinates'] = '50.853134,14.69983';
					$item['name'] = 'Gesteinstafel Jonsdorf';
					$item['alias'] = 'gesteinstafel_jonsdorf';
					$item['popuptext'] = 'Wer einen Überblick über die Geologie des Zittauer Gebirges erhalten möchte, sollte sich die Gesteinstafel in Jonsdorf näher anschauen. Auf ihr werden anschaulich die unterschiedlich aufgebauten Gesteine dargestellt.<a href="http://www.naturparkblicke.de/images/Bilder/allg_sonstiges_gesteinstafel_jonsdorf.jpg" target="_blank" title="Gesteinstafel Jonsdorf">Gesteinstafel Jonsdorf</a>';
					break;
				case 3:
					$item['coordinates'] = '50.84601858,14.69342505';
					$item['name'] = 'Große und Kleine Orgel - Geotop';
					$item['alias'] = 'große_und_kleine_orgel_geotop';
					$item['popuptext'] = 'Die Große und die Kleine Orgel sehen aus wie typische Basaltsäulen, aber dieser Eindruck täuscht. Tatsächlich bestehen sie aus Sandstein, der zur Zeit des tertiären Vulkanismus durch Kontakt mit heißem Magma teilweise aufgeschmolzen wurde und in Säulenform erstarrte.<a href="http://naturparkblicke.de/images/Bilder/allg_landschaft_grosse_kleine_orgel.jpg" target="_blank" title="Große und Kleine Orgel - Geotop">Große und Kleine Orgel - Geotop</a>';
					break;
				case 4:
					$item['coordinates'] = '50.84655558000001,14.69498983';
					$item['name'] = 'HSZ Jonsdorfer Felsenstadt mit Buchberg im Hintergrund';
					$item['alias'] = 'hsz_jonsdorfer_felsenstadt_mit_buchberg_im_hintergrund';
					$item['popuptext'] = 'Teile der Jonsdorfer Felsenstadt wurden als Horstschutzzonen ausgeschrieben, da hier seltene Felsbrüter ihren Lebensraum finden. Während der Brutsaison dürfen diese nicht betreten werden.<a href="http://naturparkblicke.de/images/Bilder/allg_schutzgebiet_jonsdorfer_felsenstadt.jpg" target="_blank" title="HSZ Jonsdorfer Felsenstadt mit Buchberg im Hintergrund">HSZ Jonsdorfer Felsenstadt mit Buchberg im Hintergrund</a>';
					break;
				case 5:
					$item['coordinates'] = '50.849294,14.648184';
					$item['name'] = 'Naturschutzgebiet Lausche';
					$item['alias'] = 'naturschutzgebiet_lausche';
					$item['popuptext'] = '<a href="http://www.naturparkblicke.de/images/Bilder/allg_schutzgebiet_lauschewald_im_winter.jpg" target="_blank" title="Naturschutzgebiet Lausche">Naturschutzgebiet Lausche</a>';
					break;
				case 6:
					$item['coordinates'] = '50.928023,14.671194';
					$item['name'] = 'Weißer Stein';
					$item['alias'] = 'weisser_stein';
					$item['popuptext'] = '<a href="http://www.naturparkblicke.de/images/Bilder/allg_schutzgebiet_weisser_stein_forstenberg.jpg" target="_blank" title="Weißer Stein">Weißer Stein</a>';
					break;
				case 7:
					$item['coordinates'] = '50.845419,14.740219';
					$item['name'] = 'Hausgrundbach in TWSZ im Hausgrund / Oybin';
					$item['alias'] = 'hausgrundbach_in_twsz_im_hausgrund_oybin';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/stories/allg_wasser-klima_hausgrundbach.jpg" target="_blank" title="Hausgrundbach in TWSZ im Hausgrund/ Oybin">Hausgrundbach in TWSZ im Hausgrund/ Oybin</a></a>';
					break;
				case 8:
					$item['coordinates'] = '50.881702,14.708877';
					$item['name'] = 'Bertsdorf mit Regenwolke im Hintergrund';
					$item['alias'] = 'bertsdorf_mit_regenwolke_im_hintergrund';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/allg_wasser_klima_bertsdorf_regen.jpg" target="_blank" title="Bertsdorf mit Regenwolke im Hintergrund">Bertsdorf mit Regenwolke im Hintergrund</a>';
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



		
		// START FNDGEBIET
		$category_FNDGebiet = [
			'title' => 'FNDGebiet',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'fndgebiet',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/grn-stars.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_FNDGEBIET_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_FNDGebiet)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_fndgebiet = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_fndgebiet,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/grn-stars.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/grn-stars.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.88482203,14.64417458';
					$item['name'] = 'Allee an der Alten Landstraße';
					$item['alias'] = 'allee_an_der_alten_landstraße';
					$item['popuptext'] = 'Auf einer Länge von 320 Metern wurde an der Alten Landstraße in Größschönau ein Flächennaturdenkmal ausgewiesen, um die hier ehemals angelegte Allee zu schützen. Insgesamt weist das Flächennaturdenkmal eine Fläche von 0,4 Hektar auf.';
					break;
				case 2:
					$item['coordinates'] = '50.849084,14.69866';
					$item['name'] = 'Drei Tische';
					$item['alias'] = 'drei_tische';
					$item['popuptext'] = 'Die Drei Tische sind in den Jonsdorfer Mühlsteinbrüchen zu finden und bemerkenswert, da hier Eisenoxidausfällungen zu nierenförmigen Formen geführt haben.';
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

		$this->app->setUserState('sampledata.agosms.items.catId_fndgebiet', $catId_fndgebiet);
		// ENDE FNDGEBIET
		
		


		// START SONSTIGES
		$category_Sonstiges = [
			'title' => 'Sonstiges',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'sonstiges',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/open-diamond.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_SONSTIGES_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Sonstiges)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_sonstiges = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_sonstiges,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/open-diamond.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/open-diamond.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.83447314,14.72125826';
					$item['name'] = 'Wiesenbeweidung mit Schafen bei Oybin, Hain';
					$item['alias'] = 'wiesenbeweidung_mit_schafen_bei_oybin';
					$item['popuptext'] = 'Die Beweidung von Wiesen mit Schafen stellt eine extensive Form der Bewirtschaftung dar, was zur Schonung der Artenvielfalt beiträgt. Auf den Einsatz schwerer Maschinen und großer Mengen Düngemittel wird verzichtet.<a href="Hain_Schafe_6.jpg" target="_blank" title="Wiesenbeweidung mit Schafen bei Oybin, Hain">Wiesenbeweidung mit Schafen bei Oybin, Hain</a>';
					break;
				case 2:
					$item['coordinates'] = '50.92224424000001,14.6658897399902';
					$item['name'] = 'Hofebusch';
					$item['alias'] = 'hofebusch';
					$item['popuptext'] = 'Als Hofebusch wird das große zusammenhängende Waldgebiet zwischen Spitzkunnersdorf, Hainewalde und Großschönau bezeichnet. Das Gebiet wird größtenteils von Fichtenforst eingenommen, der an manchen Stellen durchaus strukturreich ist. Auf dem Forstenberg kann jedoch auch ein Buchenwald vorgefunden werden.';
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

		$this->app->setUserState('sampledata.agosms.items.catId_sonstiges', $catId_sonstiges);
		// ENDE SONSTIGES
		
		


		// START SEHENSWERT
		$category_Sehenswert = [
			'title' => 'Sehenswert',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'sehenswert',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/poi.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_SEHENSWERT_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Sehenswert)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_sehenswert = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_sehenswert,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/poi.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/poi.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.8658,14.64165';
					$item['name'] = 'Sängerhöhe - offenes natürliche Felsbiotop - Vulkanschlot';
					$item['alias'] = 'saengerhöhe';
					$item['popuptext'] = 'An der Sängerhöhe befindet sich ein offenes natürliches Felsbiotop, das mitten im Wald aufragt. Dabei handelt es sich um Basaltsäulen, die zu einem Basaltgang gehören, der eine Länge von 100 m und eine Breite von 30 m hat. Einst lagerten hier auch vulkanische Tuffe, welche aber inzwischen verwittert sind.<a href="sonstiges_geotop_saengerhoehe.jpg" target="_blank" title="Sängerhöhe - offenes natürliche Felsbiotop > Vulkanschlot ">Sängerhöhe - offenes natürliche Felsbiotop > Vulkanschlot </a>';
					break;
				case 2:
					$item['coordinates'] = '50.846578,14.696945';
					$item['name'] = 'Steinbruchschmiede';
					$item['alias'] = 'steinbruchschmiede';
					$item['popuptext'] = 'Die Steinbruchschmiede ist eng mit der Tradition des Mühlsteinabbaus verbunden. Einst wurde die Schmiede genutzt um die Werkzeuge für den Abbau des harten Sandsteins zu bearbeiten. Zudem diente sie als zentraler Sammel- und Informationspunkt für die Bergarbeiter. Sie ist heute als Museum ausgebaut.';
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

		$this->app->setUserState('sampledata.agosms.items.catId_sehenswert', $catId_sehenswert);
		// ENDE SEHENSWERT




		// START ORT_NP
		$category_Ort_NP = [
			'title' => 'Ort_NP',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'ort_np',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/triangle.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_ORT_NP_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Ort_NP)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_ort_np = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_ort_np,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/triangle.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/triangle.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.8849294,14.7340034';
					$item['name'] = 'Bertsdorf';
					$item['alias'] = 'bertsdorf';
					$item['popuptext'] = 'Die Gemeinde Bertsdorf wurde im 13. Jahrhundert als Waldhufendorf gegründet. Der Name des Dorfes deutet an, dass der Gründer den Namen Bertram trug. Zu den Sehenswürdigkeiten des Ortes zählen u. .a. das Schloss Althörnitz (heute ein Schlosshotel), die Barockkirche, zahlreiche Umgebindehäuser mit reich verzierten Sandsteintürstöcken und der Breiteberg.';
					break;
				case 2:
					$item['coordinates'] = '50.8603334,14.79146';
					$item['name'] = 'Eichgraben';
					$item['alias'] = 'eichgraben';
					$item['popuptext'] = 'Das im Quellbereich des Pfaffenbachs liegende Eichgraben ist ein Ortsteil Zittaus und wurde bereits im Jahr 1582 das erste Mal erwähnt. Bedeutend für die Entwicklung des Ortes war die Pestepidemie 1599, in Folge derer sich viele Einwohner dicht besiedelter Orte in Außenbereichen niederließen. Haupterwerbszweige waren Waldarbeit, Hausweberei und seit Mitte des 19. Jh. auch Bergbau. Zurzeit leben 765 Menschen in Eichgraben (03.2011).';
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

		$this->app->setUserState('sampledata.agosms.items.catId_ort_np', $catId_ort_np);
		// ENDE ORT_NP
		
		


		// START GIPFEL_OHNE_PANO
		$category_Gipfel_ohne_Pano = [
			'title' => 'Gipfel_ohne_Pano',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'gipfel_ohne_pano',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/placemark_circle.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_GIPFEL_OHNE_PANO_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Gipfel_ohne_Pano)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_gipfel_ohne_pano = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_gipfel_ohne_pano,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/placemark_circle.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/placemark_circle.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.844664,14.675921';
					$item['name'] = 'Falkenstein';
					$item['alias'] = 'falkenstein';
					$item['popuptext'] = 'Der Falkenstein liegt direkt an der Grenze zwischen Deutschland und der Tschechischen Republik südwestlich der Jonsdorfer Felsenstadt. Er ist wie einige andere Felsen in der Region ein beliebtes Ziel für Kletterer.';
					break;
				case 2:
					$item['coordinates'] = '50.848958,14.76137';
					$item['name'] = 'Felsgebilde Schildkröte';
					$item['alias'] = 'felsgebilde_schildkroete';
					$item['popuptext'] = 'Das Felsgebilde Schildkröte ist eines der markanten Gebilde auf dem Töpfer. Auf dem ersten Blick kann man erkennen, warum der Fels diesen Namen trägt. Sie wurde zum Naturdenkmal erklärt. Rund um die Schildkröte wächst Calluna-Heide auf Sandstein.';
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

		$this->app->setUserState('sampledata.agosms.items.catId_gipfel_ohne_pano', $catId_gipfel_ohne_pano);
		// ENDE GIPFEL_OHNE_PANO
		
		


		// START GIPFEL_MIT_PANO
		$category_Gipfel_mit_Pano = [
			'title' => 'Gipfel_mit_Pano',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'gipfel_mit_pano',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/target.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_GIPFEL_MIT_PANO_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Gipfel_mit_Pano)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_gipfel_mit_pano = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 41; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_gipfel_mit_pano,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/target.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/target.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.846428,14.740142';
					$item['name'] = 'Berg Oybin';
					$item['alias'] = 'berg_oybin';
					$item['popuptext'] = 'Der Berg Oybin ist wegen seiner steilen Sandsteinwände einer der bekanntesten Berge im Zittauer Gebirge. Der 515 m hohe Berg überragt Oybin und wurde schon früh als  gut geeigneter Platz zum Bau einer Burg erkannt. Heute können der Berg und die Burg bequem von Oybin aus auf mehreren Wegen erreicht werden.<a href="http://naturparkblicke.de/images/Bilder/pano_standort_oybin.jpg" target="_blank" title="Berg Oybin ">Berg Oybin </a>';
					break;
				case 2:
					$item['coordinates'] = '50.902045,14.688678';
					$item['name'] = 'Breiteberg';
					$item['alias'] = 'breiteberg';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_carolafelsen.jpg" target="_blank" title="Breiteberg">Breiteberg</a>';
					break;
				

				case 3:
					$item['coordinates'] = '50.8477,14.697507';
					$item['name'] = 'Carolafelsen bei Jonsdorf';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_breiteberg.jpg" target="_blank" title="Carolafelsen bei Jonsdorf">Carolafelsen bei Jonsdorf</a>';
					break;
				case 4:
					$item['coordinates'] = '50.930927,14.570997';
					$item['name'] = 'Frenzelsberg';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_frenzelsberg.jpg" target="_blank" title="Frenzelsberg">Frenzelsberg</a>';
					break;
				case 5:
					$item['coordinates'] = '50.825647,14.727484';
					$item['name'] = 'Hochwaldturm mit Blick vom Nonnenfelsen';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_hochwaldturm.jpg" target="_blank" title="Hochwaldturm mit Blick vom Nonnenfelsen">Hochwaldturm mit Blick vom Nonnenfelsen</a>';
					break;
				case 6:
					$item['coordinates'] = '50.835221,14.718833';
					$item['name'] = 'Johannisstein bei Oybin, Hain';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_johannisstein.jpg" target="_blank" title="Johannisstein bei Oybin, Hain">Johannisstein bei Oybin, Hain</a>';
					break;
				case 7:
					$item['coordinates'] = '50.91742,14.67833';
					$item['name'] = 'Lindeberg bei Hainwalde';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_lindeberg.jpg" target="_blank" title="Lindeberg bei Hainwalde">Lindeberg bei Hainwalde</a>';
					break;
				case 8:
					$item['coordinates'] = '50.89351899999999,14.778412';
					$item['name'] = 'Olbersdorfer See';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_o-see.jpg" target="_blank" title="Olbersdorfer See">Olbersdorfer See</a>';
					break;
				case 9:
					$item['coordinates'] = '50.82591799999999,14.81327';
					$item['name'] = 'Popova Skala - Pfaffenstein';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_popova_skala.jpg" target="_blank" title="Popova Skala - Pfaffenstein">Popova Skala - Pfaffenstein</a>';
					break;
				case 10:
					$item['coordinates'] = '50.830526,14.748176';
					$item['name'] = 'Schöne Aussicht in Lückendorf';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_schoene_aussicht.jpg" target="_blank" title="Schöne Aussicht in Lückendorf">Schöne Aussicht in Lückendorf</a>';
					break;
				case 11:
					$item['coordinates'] = '50.90678799999999,14.757508';
					$item['name'] = 'Schülerbusch bei Mittelherwigsdorf';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_schuelerbusch.jpg" target="_blank" title="Schülerbusch bei Mittelherwigsdorf">Schülerbusch bei Mittelherwigsdorf</a>';
					break;
				case 12:
					$item['coordinates'] = '50.88435,14.71125';
					$item['name'] = 'Seidelsberg, Bertsdorf ins Zittauer Becken und östliches Zittauer Gebirge';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_bertsdorf.jpg" target="_blank" title="Seidelsberg, Bertsdorf ins Zittauer Becken und östliches Zittauer Gebirge">Seidelsberg, Bertsdorf ins Zittauer Becken und östliches Zittauer Gebirge</a>';
					break;
				case 13:
					$item['coordinates'] = '50.870122,14.778547';
					$item['name'] = 'Napoleon-Linde bei Olbersdorf';
					$item['popuptext'] = 'Die Napoleonlinde ist ein markanter Einzelbaum, der am Mittelweg zwischen Zittau und dem Zittau Gebirge liegt. Über diesen Weg kann man das Zittauer Gebirge bequem zu Fuß oder mit dem Rad erreichen. Einst soll Napoleon neben dieser Linde gestanden haben, dem sie aus diesem Grund ihren Namen verdankt.<a href="http://naturparkblicke.de/images/Bilder/pano_standort_napoleonlinde.jpg" target="_blank" title="Napoleon-Linde bei Olbersdorf">Napoleon-Linde bei Olbersdorf</a>';
					break;
				case 14:
					$item['coordinates'] = '50.94196000000001,14.6592';
					$item['name'] = 'Großer Stein im Dezember - Eichenwald trockener Standorte, Trockengebüsch sowie Goethekopf';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_grosser_stein.jpg" target="_blank" title="Großer Stein im Dezember - Eichenwald trockener Standorte, Trockengebüsch sowie Goethekopf">Großer Stein im Dezember - Eichenwald trockener Standorte, Trockengebüsch sowie Goethekopf</a>';
					break;
				case 15:
					$item['coordinates'] = '50.850323,14.683629';
					$item['name'] = 'Nonnenfelsen';
					$item['popuptext'] = 'Die Nonnenfelsen zählen zu den beliebtesten Ausflugszielen um Jonsdorf. Sie können durch einen Treppenweg oder üben den Jonsdorfer Klettersteig erklommen werden. Die Aussicht ist beeindruckend, wie das Panorama "Nonenfelsen" beweist. Unterhalb des Treppenaufstiegs führt ein kurzer Weg zu den "Zigeunerstuben", einem ehemaligen Basaltgang.<a href="http://naturparkblicke.de/images/Bilder/pano_standort_nonnenfelsen.jpg" target="_blank" title="Nonnenfelsen">Nonnenfelsen</a>';
					break;
				case 16:
					$item['coordinates'] = '50.84544607,14.74612662';
					$item['name'] = 'Scharfenstein - verkieselter Sandstein';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_scharfenstein.jpg" target="_blank" title="Scharfenstein - verkieselter Sandstein">Scharfenstein - verkieselter Sandstein</a>';
					break;
				case 17:
					$item['coordinates'] = '50.878425,14.702493';
					$item['name'] = 'Steinberg bei Bertsdorf';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_steinberg.jpg" target="_blank" title="Steinberg bei Bertsdorf">Steinberg bei Bertsdorf</a>';
					break;
				case 18:
					$item['coordinates'] = '50.84949700000001,14.761482';
					$item['name'] = 'Töpfer';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_toepfer.jpg" target="_blank" title="Töpfer">Töpfer</a>';
					break;
				case 19:
					$item['coordinates'] = '50.849125,14.646629';
					$item['name'] = 'Lausche im Winter';
					$item['popuptext'] = '<a href="http://www.naturparkblicke.de/images/htmlmaps/lauschepano_3_picto.jpg" target="_blank" title="Lausche im Winter">Lausche im Winter</a>';
					break;
				case 20:
					$item['coordinates'] = '50.941813,14.603573';
					$item['name'] = 'Windmühlberg Seifhennersdorf';
					$item['popuptext'] = '<a href="http://naturparkblicke.de/images/Bilder/pano_standort_windmuehlenberg.jpg" target="_blank" title="Windmühlberg Seifhennersdorf">Windmühlberg Seifhennersdorf</a>';
					break;
				case 21:
					$item['coordinates'] = '50.84638699999999,14.740148';
					$item['name'] = 'Panorama vom Hausgrundfelsen';
					$item['popuptext'] = '-<a href="http://naturparkblicke.de/images/Bilder/panorama_hausgrundfelsen.jpg" target="_blank" title="Panorama vom Hausgrundfelsen">Panorama vom Hausgrundfelsen</a>';
					break;
				case 22:
					$item['coordinates'] = '50.87009795,14.77845669';
					$item['name'] = 'Panorama Napoleon-Linde';
					$item['popuptext'] = 'Von der sehenswerten Napoleonlinde aus hat man einen erstaunlich guten Ausblick auf das Zittauer Becken und das Isergebirge auf tschechischer und polnischer Seite. Blickt man nach Südwesten, erheben sich beeindruckend der Töpfer und der Ameisenberg als erste Gipfel des Zittauer Gebirges.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_napoleonslinde_picto.jpg" target="_blank" title="Panorama Napoleon-Linde">Panorama Napoleon-Linde</a>';
					break;
				case 23:
					$item['coordinates'] = '50.8450927,14.74326285';
					$item['name'] = 'Panorama Berg Oybin';
					$item['popuptext'] = 'Der Berg Oybin ist wegen seiner steilen Sandsteinwände einer der bekanntesten Berge im Zittauer Gebirge. Der 515 m hohe Berg überragt Oybin und wurde schon früh als  gut geeigneter Platz zum Bau einer Burg erkannt. Die Burg wurde im frühen 13. Jahrhundert erstmals errichtet und 1291 zerstört. Bis zur Reformation wurde die neu aufgebaute Burg als Cölestiner-Kloster genutzt.<a href="http://www.naturparkblicke.de/images/htmlmaps/oybin_10_picto.jpg" target="_blank" title="Panorama Berg Oybin">Panorama Berg Oybin</a>';
					break;
				case 24:
					$item['coordinates'] = '50.89213000000001,14.70447';
					$item['name'] = 'Panorama Breiteberg';
					$item['popuptext'] = 'Der Breiteberg ist 510 m hoch und der Hausberg der Ortschaft Hainewalde. Er ist aus dem Vulkangestein Phonolith aufgebaut, der einer Basaltdecke aufliegt. Auf dem Berg findet sich die Bergbaude und ein Aussichtsturm, dessen Schlüssel man in der Bergbaude erhält. Um den Breiteberg führt ein Rundweg, der den Besucher auch an der Querxhöhle und den Querxbrunnen vorbeiführt.<a href="http://www.naturparkblicke.de/images/htmlmaps/breiteberg_picto.jpg" target="_blank" title="Panorama Breiteberg">Panorama Breiteberg</a>';
					break;
				case 25:
					$item['coordinates'] = '50.8477,14.697507';
					$item['name'] = 'Panorama Carolafelsen bei Jonsdorf';
					$item['popuptext'] = 'Der Sandsteinberg Carolafelsen hat eine Höhe von 569 m ü. NN und bietet einen sehr guten Ausblick auf die Ortschaft Jonsdorf, die Nonnenfelsen, Lausche und das Oberlausitzer Bergland. Zu Füßen des Carolafelsens liegen die Jonsdorfer Mühlsteinbrüche mit ihren zahlreichen besonderen Felsgebildet, z.B. Drei Tische, Mausefalle, sowie kleine und große Orgel<a href="http://www.naturparkblicke.de/images/htmlmaps/carolafelsen_picto.jpg" target="_blank" title="Panorama Carolafelsen bei Jonsdorf">Panorama Carolafelsen bei Jonsdorf</a>';
					break;
				case 26:
					$item['coordinates'] = '50.930927,14.570997';
					$item['name'] = 'Panorama Frenzelsberg';
					$item['popuptext'] = 'Aus Basalt aufgebaut ist der Frenzelsberg, der in der Nähe der Grenze zur Tschechischen Republik aufragt. Auf ihm finden sich säulige Basaltabsonderungen, weshalb der Berg zum geologischen Flächennaturdenkmal erklärt wurde.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_frenzelsberg_picto.jpg" target="_blank" title="Panorama Frenzelsberg">Panorama Frenzelsberg</a>';
					break;
				case 27:
					$item['coordinates'] = '50.94196000000001,14.6592';
					$item['name'] = 'Panorama Großer Stein mit Goethekopf bei Leutersdorf';
					$item['popuptext'] = 'Der Große Stein ist ein Phonolithberg, der zwei Gipfel aufweist, wobei der höchste Punkt 471 m hoch ist. Der kleinere der beiden Gipfel wird auch als Goethekopf bezeichnet, da in der Felsformation der liegende Kopf des namhaften Schriftstellers erkannt werden kann. Auch finden sich hier mit Flechten bewachsene Säulen aus Phonolith.<a href="http://www.naturparkblicke.de/images/htmlmaps/groer-stein_29_picto.jpg" target="_blank" title="Panorama Großer Stein mit Goethekopf bei Leutersdorf">Panorama Großer Stein mit Goethekopf bei Leutersdorf</a>';
					break;
				case 28:
					$item['coordinates'] = '50.825647,14.727484';
					$item['name'] = 'Panorama Hochwaldturm';
					$item['popuptext'] = 'Der Hochwald ist mit 749 m Höhe der zweithöchste Berg des Zittauer Gebirges. Den Gipfel der Phonolithkuppe schmückt ein Aussichtsturm, den es lohnt zu besteigen, denn von hier aus hat man eine prächtige Aussicht auf das Zittauer Gebirge und das Oberlausitzer Gebirge. Direkt über den südlichen Gipfel führt die Grenze zwischen Deutschland und der Tschechischen Republik.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_hochwald_picto.jpg" target="_blank" title="Panorama Hochwaldturm">Panorama Hochwaldturm</a>';
					break;
				case 29:
					$item['coordinates'] = '50.83637441,14.72073395';
					$item['name'] = 'Panorama Johannisstein bei Oybin, Hain';
					$item['popuptext'] = 'Über den Ortsteil Hain thront der Johannisstein, der im Grunde einer von drei aus Basaltsäulen verwitterten Steinen ist. Er befindet sich genau an der Grenze zur Tschechischen Republik und bietet einen lohnenswerten Ausblick auf das Zittauer Gebirge. An seinem Nordosthang befindet sich ein Ski- und Rodelhang.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_johannisstein_10_picto.jpg" target="_blank" title="Panorama Johannisstein bei Oybin, Hain">Panorama Johannisstein bei Oybin, Hain</a>';
					break;
				case 30:
					$item['coordinates'] = '50.91793273,14.68336492';
					$item['name'] = 'Panorama Lindeberg bei Hainwalde, Blick über Östliche Oberlausitz';
					$item['popuptext'] = 'Der Lindeberg bietet eine schöne Aussicht auf die östliche Oberlausitz sowie auf das Iser- und Jeschkengebirge in der Tschechischen Republik. <a href="http://www.naturparkblicke.de/images/htmlmaps/lindeberg_picto.jpg" target="_blank" title="Panorama Lindeberg bei Hainwalde, Blick über Östliche Oberlausitz ">Panorama Lindeberg bei Hainwalde, Blick über Östliche Oberlausitz </a>';
					break;
				case 31:
					$item['coordinates'] = '50.850323,14.683629';
					$item['name'] = 'Panorama Nonnenfelsen';
					$item['popuptext'] = 'Die Nonnenfelsen zählen zu den beliebtesten Ausflugszielen um Jonsdorf. Sie können durch einen Treppenweg oder üben den Jonsdorfer Klettersteig erklommen werden. Auf dem dem Nonnenfelsen findet sich eine Aussichtsplattform und ein Gasthaus, in dem sich der Wanderer stärken kann.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_nonnenfelsen.jpg" target="_blank" title="Panorama Nonnenfelsen">Panorama Nonnenfelsen</a>';
					break;
				case 32:
					$item['coordinates'] = '50.89351899999999,14.77841';
					$item['name'] = 'Panorama Olbersdorfer Tagebau';
					$item['popuptext'] = 'Im Sommer läd der Olbersdorfer See zum Baden ein. Er ist ein junger See, denn erst 1999 war die Flutung des Braunkohletagebaurestlochs abgeschlossen und der Badebetrieb konnte beginnen. Bis zur Wende wurde dort Braunkohle abgebaut, danach wurde der Tagebau geschlossen und damit begonnen, das umgebende Gelände zu rekultivieren.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_o-see_picto.jpg" target="_blank" title="Panorama Olbersdorfer Tagebau">Panorama Olbersdorfer Tagebau</a>';
					break;
				case 33:
					$item['coordinates'] = '50.82674747,14.81511289';
					$item['name'] = 'Panorama Popova Skala - Pfaffenstein';
					$item['popuptext'] = 'Auf Seite der Tschechischen Republik und damit außerhalb des Naturparks liegt der 565 m hohe Pfaffenstein. Er besteht aus Sandstein, der aufgrund einer geologischen Störung (Lausitzer Störung) angehoben wurde. Daher ist der Sandstein des Pfaffenbergs schiefgerichtet.<a href="http://www.naturparkblicke.de/images/htmlmaps/popova_skala_picto.jpg" target="_blank" title="Panorama Popova Skala - Pfaffenstein">Panorama Popova Skala - Pfaffenstein</a>';
					break;
				case 34:
					$item['coordinates'] = '50.84075799999999,14.758219';
					$item['name'] = 'Panorama Scharfenstein';
					$item['popuptext'] = 'Der Scharfenstein ist ein Sandsteingipfel mit einer Höhe von 569 m. Er kann über Treppen erklommen werden. Zur Zeit des Vulkanismus im Tertiär befand sich an dieser Stelle ein Schlot, über den kieselsäurehaltige Thermalwässer zugeführt wurden. Dadurch wurde der Sandstein des Scharfensteins verkittet und gehärtet, sodass er weitesgehend vor Verwitterung geschützt war.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_scharfenstein_picto.jpg" target="_blank" title="Panorama Scharfenstein">Panorama Scharfenstein</a>';
					break;
				case 35:
					$item['coordinates'] = '50.830526,14.748176';
					$item['name'] = 'Panorama Schöne Aussicht in Lückendorf';
					$item['popuptext'] = 'Der Aussichtspunkt "Schöne Aussicht" bei Lückendorf bietet eine reizvolle Aussicht nach Süden in Richtung Tschechische Republik und ist direkt an der Straße gelegen.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_schoene_aussicht_lueckendorf.jpg" target="_blank" title="Panorama Schöne Aussicht in Lückendorf">Panorama Schöne Aussicht in Lückendorf</a>';
					break;
				case 36:
					$item['coordinates'] = '50.90678799999999,14.757508';
					$item['name'] = 'Panorama Schülerbusch bei Mittelherwigsdorf';
					$item['popuptext'] = 'Der Schülerberg ist eng verbunden mit dem Schülerbusch und weist einen naturnahen feuchten Eichen-Hainbuchen-Winterlindenwald auf. Der Berg selbst ist aus Phonolith aufgebaut, was auf seine Vergangenheit als Vulkan hinweist. Der Schülerbusch kann auf einem die Sinne anregenden Barfußweg erkundet werden.<a href="http://www.naturparkblicke.de/images/htmlmaps/schlerbusch_12_picto.jpg" target="_blank" title="Panorama Schülerbusch bei Mittelherwigsdorf">Panorama Schülerbusch bei Mittelherwigsdorf</a>';
					break;
				case 37:
					$item['coordinates'] = '50.88435,14.71125';
					$item['name'] = 'Panorama Seidelsberg, Bertsdorf ins Zittauer Becken und östliches Zittauer Gebirge';
					$item['popuptext'] = 'Einen sehr schönen Ausblick auf das östliche Zittauer Gebirge und das Zittauer Becken bietet der Standort am Seidelsberg, der zum FFH-Gebiet "Basalt- und Phonolithkuppen der Oberlausitz" gehört. Der Seidelsberg, auch Roscherberg genannt, ist 433 m hoch und aus Phonolith aufgebaut.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_bertsdorf_15_picto.jpg" target="_blank" title="Panorama Seidelsberg, Bertsdorf ins Zittauer Becken und östliches Zittauer Gebirge">Panorama Seidelsberg, Bertsdorf ins Zittauer Becken und östliches Zittauer Gebirge</a>';
					break;
				case 38:
					$item['coordinates'] = '50.941738,14.603586';
					$item['name'] = 'Panorama Windmühlberg bei Seifhennersdorf';
					$item['popuptext'] = 'Der Sommerberg ist 496 m hoch und und liegt südöstlich von Lückendorf. Der Skiwanderweg "Oberer langer Grundweg" führt an dieser Kuppe vorbei.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_windmuehlenberg_picto.jpg" target="_blank" title="Panorama Windmühlberg bei Seifhennersdorf">Panorama Windmühlberg bei Seifhennersdorf</a>';
					break;
				case 39:
					$item['coordinates'] = '50.878339,14.702141';
					$item['name'] = 'Panorama Steinberg bei Bertsdorf';
					$item['popuptext'] = 'Der 443 m hohe Steinberg überragt wie Seidelsberg und Breiteberg den Ort Bertsdorf. Wie diese ist der Berg aus Phonolith aufgebaut. Er ist leicht bewaldet und bietet eine schöne Aussicht auf das westliche Zittauer Gebirge sowie das Iser- und Jesckengebirge.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_steinberg_7_picto.jpg" target="_blank" title="Panorama Steinberg bei Bertsdorf">Panorama Steinberg bei Bertsdorf</a>';
					break;
				case 40:
					$item['coordinates'] = '50.84949700000001,14.761482';
					$item['name'] = 'Panorama Töpfer';
					$item['popuptext'] = 'Der von Zittau aus gut sichtbare Töpfer ist von Osten aus gesehen der erste Berg des Zittauer Gebirges. Er ist 582 m hoch und bietet eine sehr schöne Aussicht auf das Zittauer Becken sowie zahlreiche sehenswerte Sandsteinformationen, wie Brütende Henne oder Schildkröte. Das Felsentor auf dem Gipfel kann über Treppen bestiegen werden.<a href="http://www.naturparkblicke.de/images/htmlmaps/pano_toepfer_11_500px_picto.jpg" target="_blank" title="Panorama Töpfer">Panorama Töpfer</a>';
					break;
				case 41:
					$item['coordinates'] = '50.849125,14.646629';
					$item['name'] = 'Panorama von der Lausche im Frühjahr';
					$item['popuptext'] = 'Der höchste Gipfel des Zittauer Gebirges ist mit 793 m die Lausche, welche aus Phonolith aufgebaut ist, der einer Bassaltschicht und vulkanischen Tuffen aufliegt. Sie bietet ein besonderes montanes Mikroklima, sodass hier teilweise auch Kaltzeitzeugen  (z. B. Alpenspitzmaus) gefunden werden können. Wegen des wunderschönen Ausblicks sollte jeder Besucher die Lausche einmal bestiegen haben.<a href="http://www.naturparkblicke.de/images/htmlmaps/lauschepano_3_picto.jpg" target="_blank" title="Panorama von der Lausche im Frühjahr">Panorama von der Lausche im Frühjahr</a>';
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

		$this->app->setUserState('sampledata.agosms.items.catId_gipfel_mit_pano', $catId_gipfel_mit_pano);
		// ENDE GIPFEL_MIT_PANO
		
		

		// START FFHGEBIET
		$category_FFHGebiet = [
			'title' => 'FFHGebiet',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'ffhgebiet',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/ylw-stars.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_FFHGEBIET_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_FFHGebiet)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_ffhgebiet = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_ffhgebiet,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/ylw-stars.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/ylw-stars.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.89141000000001,14.7033';
					$item['name'] = 'FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Breiteberg"';
					$item['alias'] = 'breiteberg';
					$item['popuptext'] = 'Der Breiteberg ist aus Basalt und Phonolith aufgebaut, was ein gutes Ausgangsgestein für nährstoffreiche Böden ist. Unter anderem wegen dieser Tatsache finden sich am Breiteberg verschiedenste Biotope und seltene Pflanzen: Nadelwald auf der Nordseite, auf der Südseite ein Laubmischwald mit Elementen des Labkraut-Eichen-Hainbuchenwalds und ein Silikattrockenrasen.<a href="http://naturparkblicke.de/images/Bilder/ffh_spa_breiteberg.jpg" target="_blank" title="FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz  "Breiteberg"">FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz  "Breiteberg"</a>';
					break;
				case 2:
					$item['coordinates'] = '50.885682,14.714463';
					$item['name'] = 'FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"';
					$item['alias'] = 'seidelsberg';
					$item['popuptext'] = 'Wie der Breitberg besteht der Seidelsberg aus einer Decke aus Phonolith. Hier wächst sowohl Nadelwald als auch ein Labkraut-Eichen-Hainbuchenwald mit abwechslungsreicher Krautschicht. <a href="http://naturparkblicke.de/images/Bilder/ffh_spa_seidelsberg.jpg" target="_blank" title="FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"">FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"</a>';
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

		$this->app->setUserState('sampledata.agosms.items.catId_ffhgebiet', $catId_ffhgebiet);
		// ENDE FFHGEBIET




		// START GEWAESSER
		$category_Gewaesser = [
			'title' => 'Gewaesser',
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agosms',
			'level' => 1,
			'alias' => 'gewaesser',
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agosms/images/water.png',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGOSMS_CATEGORY_GEWAESSER_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category_Gewaesser)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGOSMS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId_gewaesser = $categoryModel->getItem()->id;

		$mvcFactory = $this->app->bootComponent('com_agosms')->getMVCFactory();
		$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_gewaesser,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'showdefaultpin' => 2,
				'customPinSize' => '32,32',
				'customPinPath' => 'plugins/sampledata/agosms/images/water.png',
				'customPinShadowSize' => '32,32',
				'customPinShadowPath' => 'plugins/sampledata/agosms/images/water.png',
				'customPinOffset' => '16,16',
				'customPinPopupOffset' => '-3,-76',
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.88170158999999,14.67837811';
					$item['name'] = 'Goldfabianteich';
					$item['alias'] = 'goldfabianteich';
					$item['popuptext'] = 'Der Goldfabiansteich ist ein naturnaher Teich, der von einer Mischung aus Sumpfgebüsch, Röhricht und seggen- und binsenreicher Feuchtwiese umgeben ist und sich malerisch in das Bergpanorama einfügt. Er wurde deshalb zum Flächennaturdenkmal erklärt.';
					break;
				case 2:
					$item['coordinates'] = '50.87371339,14.71058607';
					$item['name'] = 'Bertsdorfer Wasser';
					$item['alias'] = 'bertsdorfer_wasser';
					$item['popuptext'] = 'Das Bertsdorfer Wasser, welches auch als &quot;Bertse&quot; bezeichnet wird, entspringt auf der Nordostseite am Pocheberg unter Basalt. Es fließt auf einer Länge von 5,1 km durch Bertsdorf und mündet schließlich im Bertsdorfer Ortsteil Hörnitz in die Mandau.';
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

		$this->app->setUserState('sampledata.agosms.items.catId_gewaesser', $catId_gewaesser);
		// ENDE GEWAESSER
		
		


		
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

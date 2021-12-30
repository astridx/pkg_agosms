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
				'showpopup' => 1,
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.83429623,14.74360525';
					$item['name'] = 'Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein';
					$item['alias'] = 'naturnaher_basenarmer_silikatfels_-_osensteine_mit_kelchstein';
					$item['popuptext'] = '<p>Die Rosensteine sind eine markante Felsgruppe im Zittauer Gebirge, die aus Sandstein aufgebaut ist. Besonders auffällig ist der Kelchstein. Im unteren Bereich ist der Kelchstein sehr schmal, was auf die unterschiedliche Härte verschieden alter Sandsteinschichten zurückzuführen ist.<br></br><a href="http://naturparkblicke.de/images/Bilder/allg_geolog_boeden_rosensteine_kelchstein.jpg" target="_blank" title="Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein">Naturnaher basenarmer Silikatfels - Rosensteine mit Kelchstein</a>';
					break;
				case 2:
					$item['coordinates'] = '50.853134,14.69983';
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.83447314,14.72125826';
					$item['name'] = 'Wiesenbeweidung mit Schafen bei Oybin, Hain';
					$item['alias'] = 'wiesenbeweidung_mit_schafen_bei_oybin';
					$item['popuptext'] = 'Die Beweidung von Wiesen mit Schafen stellt eine extensive Form der Bewirtschaftung dar, was zur Schonung der Artenvielfalt beiträgt. Auf den Einsatz schwerer Maschinen und großer Mengen Düngemittel wird verzichtet.<br></br><a href="Hain_Schafe_6.jpg" target="_blank" title="Wiesenbeweidung mit Schafen bei Oybin, Hain">Wiesenbeweidung mit Schafen bei Oybin, Hain</a>';
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.8658,14.64165';
					$item['name'] = 'Sängerhöhe - offenes natürliche Felsbiotop - Vulkanschlot';
					$item['alias'] = 'saengerhöhe';
					$item['popuptext'] = 'An der Sängerhöhe befindet sich ein offenes natürliches Felsbiotop, das mitten im Wald aufragt. Dabei handelt es sich um Basaltsäulen, die zu einem Basaltgang gehören, der eine Länge von 100 m und eine Breite von 30 m hat. Einst lagerten hier auch vulkanische Tuffe, welche aber inzwischen verwittert sind.<br></br><a href="sonstiges_geotop_saengerhoehe.jpg" target="_blank" title="Sängerhöhe - offenes natürliche Felsbiotop > Vulkanschlot ">Sängerhöhe - offenes natürliche Felsbiotop > Vulkanschlot </a>';
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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

		for ($i = 1; $i <= 2; $i++) {
			$agosmsModel = $mvcFactory->createModel('Agosm', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => '',
				'alias'    => '',
				'catid'    => $catId_gipfel_mit_pano,
				'description' => Text::_('PLG_SAMPLEDATA_AGOSMS_DESCRIPTION'),
				'popuptext' => Text::_('PLG_SAMPLEDATA_AGOSMS_POPUPTEXT'),
				'published' => 1,
				'showpopup' => 1,
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.846428,14.740142';
					$item['name'] = 'Berg Oybin';
					$item['alias'] = 'berg_oybin';
					$item['popuptext'] = 'Der Berg Oybin ist wegen seiner steilen Sandsteinwände einer der bekanntesten Berge im Zittauer Gebirge. Der 515 m hohe Berg überragt Oybin und wurde schon früh als  gut geeigneter Platz zum Bau einer Burg erkannt. Heute können der Berg und die Burg bequem von Oybin aus auf mehreren Wegen erreicht werden.<br></br><a href="http://naturparkblicke.de/images/Bilder/pano_standort_oybin.jpg" target="_blank" title="Berg Oybin ">Berg Oybin </a>';
					break;
				case 2:
					$item['coordinates'] = '50.902045,14.688678';
					$item['name'] = 'Breiteberg';
					$item['alias'] = 'breiteberg';
					$item['popuptext'] = '<br></br><a href="http://naturparkblicke.de/images/Bilder/pano_standort_carolafelsen.jpg" target="_blank" title="Breiteberg">Breiteberg</a>';
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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
				'coordinates' => '',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['coordinates'] = '50.89141000000001,14.7033';
					$item['name'] = 'FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Breiteberg"';
					$item['alias'] = 'breiteberg';
					$item['popuptext'] = 'Der Breiteberg ist aus Basalt und Phonolith aufgebaut, was ein gutes Ausgangsgestein für nährstoffreiche Böden ist. Unter anderem wegen dieser Tatsache finden sich am Breiteberg verschiedenste Biotope und seltene Pflanzen: Nadelwald auf der Nordseite, auf der Südseite ein Laubmischwald mit Elementen des Labkraut-Eichen-Hainbuchenwalds und ein Silikattrockenrasen.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_breiteberg.jpg" target="_blank" title="FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz  "Breiteberg"">FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz  "Breiteberg"</a>';
					break;
				case 2:
					$item['coordinates'] = '50.885682,14.714463';
					$item['name'] = 'FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"';
					$item['alias'] = 'seidelsberg';
					$item['popuptext'] = 'Wie der Breitberg besteht der Seidelsberg aus einer Decke aus Phonolith. Hier wächst sowohl Nadelwald als auch ein Labkraut-Eichen-Hainbuchenwald mit abwechslungsreicher Krautschicht. <br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_seidelsberg.jpg" target="_blank" title="FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"">FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"</a>';
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
				'image'=> 'plugins/sampledata/agosms/images/camera.png',
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

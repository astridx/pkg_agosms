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

		for ($i = 1; $i <= 30; $i++) {
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
				case 3:
					$item['coordinates'] = '50.8326,14.796435';
					$item['name'] = 'Natürlicher basenarmer Silikatfels';
					$item['alias'] = 'natuerlicher_basenarmer_silikatfels';
					$item['popuptext'] = 'Offene natürliche und naturnahe Felsbildungen umfassen Felsbiotope wie Felsen, Felsköpfe, Felswände, Felsbänder und Felsspalten. Auf diesen Standorten wachsen häufig nur Moose und Flechten, da die von höheren Pflanzen benötigte Bodenbildungsschicht nicht vorhanden ist. Nur in Felsspalten wachsen sogenante Felsspalten-Gesellschaften.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_felsbiotope_uhusteine.jpg" target="_blank" title="Natürlicher basenarmer Silikatfels">Natürlicher basenarmer Silikatfels</a>';
					break;
				case 4:
					$item['coordinates'] = '50.822943,14.770125';
					$item['name'] = 'Bergwiese am Sommerberg';
					
					$item['popuptext'] = 'Artenreiche Bergwiesen sind oberhalb von 500 m anzutreffen und dadurch gekennzeichnet, dass sie extensiv genutzt werden. Auf nährstoffarmen Standorten kann es Übergänge zu den Borstgrasrasen geben. Sie sind Habitate für seltene Pflanzenarten wie Zittergras und Großem Zweiblatt.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_bergwiesen.jpg" target="_blank" title="Bergwiese am Sommerberg">Bergwiese am Sommerberg</a>';
					break;
				case 5:
					$item['coordinates'] = '50.85909214,14.79680292';
					$item['name'] = '"Binsen-, Waldsimsen- und Schachtelhalmsumpf"';
					
					$item['popuptext'] = 'Auf nassen, nicht zu intensiv genutzten Flächen, bilden sich natürliche Binsen-, Waldsimsen- und Schachtelhalmsümpfe aus. Beispiele für dort anzutreffende Pflanzenarten sind Faden-Binse, Knäuel-Binse und der Teich-Schachtelhalm.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_schachtelhalmsumpf.jpg" target="_blank" title="Binsen-, Waldsimsen- und Schachtelhalmsumpf">Binsen-, Waldsimsen- und Schachtelhalmsumpf';
					break;
				case 6:
					$item['coordinates'] = '50.821845,14.725723';
					$item['name'] = 'Blockhalde an der Nordflanke vom Hochwald';
					
					$item['popuptext'] = 'Natürliche Blockhalden sind durch große unregelmäßig aufgeschüttete Gesteinsblöcke gekennzeichnet. Eine mögliche Entstehungsursache sind vergangene Felsstürze. Blockschutthalden im Zittauer Gebirge sind häufig aus Vulkangestein (Phonolith, Basalt) und damit basenreich. Wegen der besonderen Lebensbedingungen wachsen auf diesen Standorten keine Bäume, sondern überwiegend Moose und Flechten sowie Heidelbeere und Calluna-Heide.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_blockhalde.jpg" target="_blank" title="Blockhalde an der Nordflanke vom Hochwald ">Blockhalde an der Nordflanke vom Hochwald ';
					break;
				case 7:
					$item['coordinates'] = '50.848345,14.667218';
					$item['name'] = '"Bodensaurer Tannen- Fichten-Buchenwald des Berglandes, Hainsimsen-Buchenwald"';
					
					$item['popuptext'] = 'Auf sauren Böden mit mäßiger bis mittelmäßiger Feuchteversorgung kommt im Bergland natürlicherweise der mitteleuropäische Buchenwald mit sogenannten Säurezeigern (z. B. Weißliche Hainsimse, Draht-Schmiele) vor. Auch die Tanne wächst normalerweise gemeinsam mit der Buche auf diesen Standorten. Im Zittauer Gebirge kommt auch die Fichte natürlich in diesem Biotop vor.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_hainsimsen_buchenwald.jpg" target="_blank" title="Bodensaurer Tannen- Fichten-Buchenwald des Berglandes, Hainsimsen-Buchenwald">Bodensaurer Tannen- Fichten-Buchenwald des Berglandes, Hainsimsen-Buchenwald';
					break;
				case 8:
					$item['coordinates'] = '50.851322,14.650396';
					$item['name'] = 'Borstgrasrasen - Arnikawiese Waltersdorf';
					
					$item['popuptext'] = 'Da wo die Landschaft auf sauren Standorten offen gehalten wird, bilden sich Borstgrasrasen aus. Sie sind durch Nährstoffarmut gekennzeichnet und werden vom Borstgras dominiert. Eine typische Art der montanen Borstgrasrasen ist die Arnika.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_borstgrasrasen.jpg" target="_blank" title="Borstgrasrasen - Arnikawiese Waltersdorf">Borstgrasrasen - Arnikawiese Waltersdorf';
					break;
				case 9:
					$item['coordinates'] = '50.92478033,14.72361299';
					$item['name'] = '"Eichen-Hainbuchenwald, Roschertal"';
					
					$item['popuptext'] = 'Der Eichen-Hainbuchen-Wald kann ich verschiedenen Ausprägungen vorkommen, die Baumschicht wird aber in jedem Fall von der Eiche (Stieleiche oder Traubeneiche) und der Hainbuche aufgebaut. Auf trockenen Standorten ersetzt die Traubeneiche die Stieleiche, welche auf feuchten Standorten neben der Hainbuche stockt. Typische Pflanzenarten (Bsp.): Große Sternmiere, Waldmeister, Buschwindröschen.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_eichen-hainbuchenwald_roschertal.jpg" target="_blank" title="Eichen-Hainbuchenwald, Roschertal">Eichen-Hainbuchenwald, Roschertal';
					break;
				case 10:
					$item['coordinates'] = '50.868304,14.801975';
					$item['name'] = 'Eichgrabener Teich mit Röhricht eutropher Stillgewässer';
					
					$item['popuptext'] = 'Die Eichgrabener Teiche sind naturnahe Kleingewässer mit  mittlerer Nährstoffversorgung. Am Rand befindet sich Schilfröhricht, wie es üblich ist für Gewässer dieser Art. Dort wachsen v. a. Schilf, Rohrkolben und Wasserschwaden.<br></br><a href="http://www.naturparkblicke.de/images/Bilder/biotop_lrt_eichgrabener_teich_roehricht.jpg" target="_blank" title="Eichgrabener Teich mit Röhricht eutropher Stillgewässer">Eichgrabener Teich mit Röhricht eutropher Stillgewässer';
					break;
				case 11:
					$item['coordinates'] = '50.86809867,14.64091387';
					$item['name'] = '"Erlen- und Eschenwald der Auen und Quellbereiche,  naturnaher sommerkalter Berglandbach"';
					
					$item['popuptext'] = 'Der Erlen-Eschen-Wald von Auen und Quellbereichen bildet sich natürlicherweise an Standorten aus, die zeitweise stärker nass sind wie Bachauen bei Überschwemmung, in Quellbereichen oder in Niederungen von Mooren. Kennzeichnende Baumarten sind die Schwarz-Erle und die Gemeine Esche. Im Zittauer Gebirge liegt die Unterart des Erlen-Eschen-Bachwald des Berg- und Hügellands mit Bitterem Schaumkraut und Hain-Sternmiere vor.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_pfarrfloessel.jpg" target="_blank" title="Erlen- und Eschenwald der Auen und Quellbereiche,  naturnaher sommerkalter Berglandbach">Erlen- und Eschenwald der Auen und Quellbereiche,  naturnaher sommerkalter Berglandbach';
					break;
				case 12:
					$item['coordinates'] = '50.8659676,14.6380806';
					$item['name'] = 'Sängerhöhe - Ahorn und Eschenwald felsiger Schatthänge und Schluchten';
					
					$item['popuptext'] = 'Auf Standorten in felsigen und steinschuttreichen Schluchten, die selten von der Sonne erreicht werden, herrscht ein besonderes Mikroklima. Die Feuchte ist hoch und die Temperatur gering. Hier bildet sich ein Ahorn-Eschen-Schluchtwald aus. Zu finden sind hier anspruchsvolle Baumarten wie Bergahorn, Gemeine Esche, Bergulme und Sommerlinde.';
					break;
				case 13:
					$item['coordinates'] = '50.950641,14.653462';
					$item['name'] = 'Großseggenried';
					
					$item['popuptext'] = 'Großseggenriede außerhalb stehender Gewässer wachsen auf nassen wenig intensiv genutzen Standorten. Sie werden dominiert von verschiedenen Seggenarten, z. B. Schlank-Segge, Blasen-Segge oder Ufer-Segge. Da Großseggenriede häufig in Kontakt mit Nasswiesen stehen, kommen auch andere Nässezeiger hier vor.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_grossseggenriede.jpg" target="_blank" title="Großseggenried ">Großseggenried ';
					break;
				case 14:
					$item['coordinates'] = '50.87243915,14.73741526';
					$item['name'] = 'Grundbach - naturnaher sommerkalter Bach';
					
					$item['popuptext'] = 'Der Grundbach ist ein naturnaher sommerkalter Bach des Berglandes, d. h. die Fließgeschwindigkeit ist hoch und aufgrund der Lage ist die Wassertemperatur auch im Sommer niedrig. Das Bachbett ist reich an Schotter. Wegen starker Beschattung wachsen oft nur Moose und Algen im Gewässer.';
					break;
				case 15:
					$item['coordinates'] = '50.871215,14.735379';
					$item['name'] = 'Silikat-Felsspaltengesellschaften';
					
					$item['popuptext'] = 'In den Felsspalten von Sandsteinen können verschiedene Spezialisten wachsen, die an das Leben auf dem sauren Untergrund und die geringe Bodenschicht angepasst sind. Dazu gehören viele Moose und Flechten, aber auch der Tüpfelfarn, der Braunstielige Streifenfarn und Vertreter der Gattung Fetthenne, die zu den Dickblattgewächsen zählen.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_felsspaltenvegetation.jpg" target="_blank" title="Silikat-Felsspaltengesellschaften">Silikat-Felsspaltengesellschaften';
					break;
				case 16:
					$item['coordinates'] = '50.850262,14.663298';
					$item['name'] = 'Hangquellmoor am Sonneberg';
					
					$item['popuptext'] = 'Am Sonneberg liegt ein kleiner Teil des Lausche-Hochmoores auf deutscher Seite. Wesentliche Eigenschaft von Hochmooren ist deren Nährstoffarmut, die durch die alleinige Wasserzufuhr in Form von Regenwasser bedingt wird. In der sauren Umgebung wachsen nur Torfmoose und einige seltene Pflanzenarten wie der Rundblättrige Sonnentau.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_zwischenmoor.jpg" target="_blank" title="Hangquellmoor am Sonneberg">Hangquellmoor am Sonneberg';
					break;
				case 17:
					$item['coordinates'] = '50.866213,14.691757';
					$item['name'] = '"Hochstaudenflur sumpfiger Standorte an der Poche mit Mädesüße, begleitend Kleinseggenried und Nasswiese"';
					
					$item['popuptext'] = 'Auf nährstoffreichen, nassenn Flächen, die nicht bearbeitet werden, bildet sich eine typische Vegetation aus hohen mehrjährigen krautigen Pflanzen aus (Hochstauden). Typischer Vertreter einer solchen Hochstaudenflur ist das Echte Mädesüß. Häufig sind sie mit Kleinseggenrieden und Nasswiesen vergesellschaftet.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_staudenflur.jpg" target="_blank" title="Hochstaudenflur sumpfiger Standorte an der Poche mit Mädesüße, begleitend Kleinseggenried und Nasswiese">Hochstaudenflur sumpfiger Standorte an der Poche mit Mädesüße, begleitend Kleinseggenried und Nasswiese';
					break;
				case 18:
					$item['coordinates'] = '50.84747266,14.74215544';
					$item['name'] = 'Kiefernwald trockenwarmer Fels- und Sandstandorte - Hausgrundfelsen nördlich vom Berg Oybin';
					
					$item['popuptext'] = 'Die Kiefer weist eine große Toleranz hinsichtlich Feuchte und Basengehalt auf. Da andere Baumarten in den Bereichen mittlerer Ausprägung dominant sind, wird sie häufig auf Sonderstandorte verdrängt. Ein solcher Sonderstandort sind flachgründige Steilhänge und Sandstandorte. In der Bodenvegetation kommen auch Heidelbeere, Preiselbeere und Besenheide vor.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_kiefernwald.jpg" target="_blank" title="Kiefernwald trockenwarmer Fels- und Sandstandorte - Hausgrundfelsen nördlich vom Berg Oybin">Kiefernwald trockenwarmer Fels- und Sandstandorte - Hausgrundfelsen nördlich vom Berg Oybin';
					break;
				case 19:
					$item['coordinates'] = '50.88538604,14.66083874';
					$item['name'] = 'Kleinseggenried basenarmer Standorte';
					
					$item['popuptext'] = 'Kleinseggenriede werden dominiert von Seggen, die eine geringe Wuchshöhe erreichen. Sie kommen auf nassen Standorten vor, die häufig nährstoffreich sind (Niedermoorcharakter). Eine häufige Art der basenarmen Kleinseggenriede ist die Braun-Segge.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_basenreiches_kleinseggenried.jpg" target="_blank" title="Kleinseggenried basenarmer Standorte">Kleinseggenried basenarmer Standorte';
					break;
				case 20:
					$item['coordinates'] = '50.835405,14.719058';
					$item['name'] = '"magere Frischwiesen - Flachlandmähwiesen, Submontane Goldhafer-Frischwiese"';
					
					$item['popuptext'] = 'Wiesen, die extensiv bewirtschaftet werden und eine gute Versorgung mit Feuchtigkeit haben, aber nicht zu nährstoffreich sind, werden als magere Flachland-Mähwiesen bezeichnet. Wegen der Magerkeit kommen hier viel mehr Arten vor als auf Intensivgrünland.  Es werden verschiedene Ausprägungen unterschieden, die häufigste ist die Glatthafer-Wiese, die vom Glatthafer bestimmt wird.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_montane_Goldhaferwiese_Flachlandm%C3%A4hwiese.jpg" target="_blank" title="magere Frischwiesen - Flachlandmähwiesen, Submontane Goldhafer-Frischwiese ">magere Frischwiesen - Flachlandmähwiesen, Submontane Goldhafer-Frischwiese ';
					break;
				case 21:
					$item['coordinates'] = '50.923124,14.725762';
					$item['name'] = 'Mandau im Roschertal - Naturnaher sommerkalter Fluss';
					
					$item['popuptext'] = 'Die Mandau im Roschertal ist ein naturnaher sommerkalter Fluss. Sie ist hier ein typischer Fluss der Mittelgebirge mit einem gestreckten Verlauf und einem blockreichen Flussbett. Im naturnahen sommerkalten Fluss kommt verschiedene Pflanzenarten vor: Fluthahnenhußarten, sowie Moose und Algen.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_mandau.jpg" target="_blank" title="Mandau im Roschertal - Naturnaher sommerkalter Fluss">Mandau im Roschertal - Naturnaher sommerkalter Fluss';
					break;
				case 22:
					$item['coordinates'] = '50.848332,14.667646';
					$item['name'] = '"Mesophiler Buchwald des Berglandes, Waldmeister-Buchenwald"';
					
					$item['popuptext'] = 'Auf nährstoffreichen Böden, wie sie für Vulkangestein typisch sind, wächst natürlicherweise die Rot-Buche als dominierende Baumart. Im Bergland kommen Baumarten wie Bergahorn und Fichte hinzu. Da Vulkangestein als Grundlage für die Bodenbildung basische Bodenverhältnisse zur Folge hat, unterscheidet sich dieser Buchenwald vom sauren Buchenwald durch andere Arten, wie etwa Einblütiges Perlgras, Echtes Lungenkraut oder Waldgerste.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_waldmeister_buchenwald.jpg" target="_blank" title="Mesophiler Buchwald des Berglandes, Waldmeister-Buchenwald">Mesophiler Buchwald des Berglandes, Waldmeister-Buchenwald';
					break;
				case 23:
					$item['coordinates'] = '50.865561,14.691678';
					$item['name'] = '"Nasswiese, Pochewiese"';
					
					$item['popuptext'] = 'Nasswiesen sind Wiesen, die wenig gedüngt werden und auf nassen und gut mit Nährstoffen versorgten Böden vorkommen. Typische Pflanzenarten sind die Sumpfdotterblume, der Schlangen-Knöterich und Sumpf-Schachtelhalm. Besonders erwähnenswert ist das Breitblättrige Knabenkraut, das hier seinen Lebensraum findet.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_nasswiese.jpg" target="_blank" title="Nasswiese, Pochewiese">Nasswiese, Pochewiese';
					break;
				case 24:
					$item['coordinates'] = '50.884025,14.758601';
					$item['name'] = 'naturnahes ausdauerndes Kleingewässer mit Verlandungsvegetation und Schwimmblattvegetation';
					
					$item['popuptext'] = 'In manchen nährstoffreichen Seen finden sich Bereiche mit Unterwasservegetation (aus Laichkraut) und Seerosen- und Schwimmblattvegetation (Teichrose, Seerose). Auch Wasserlinsen können zu finden sein. Ein Beispiel für einen nährstoffreichen See mit Schwimmblattvegetation ist der Olbersdorfer See.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_verlandungsbereich_osee.jpg" target="_blank" title="naturnahes ausdauerndes Kleingewässer mit Verlandungsvegetation und Schwimmblattvegetation">naturnahes ausdauerndes Kleingewässer mit Verlandungsvegetation und Schwimmblattvegetation';
					break;
				case 25:
					$item['coordinates'] = '50.833271,14.773584';
					$item['name'] = 'naturnahes temporäres Kleingewässer';
					
					$item['popuptext'] = 'Naturnahe temporäre Kleingewässer werden im allgemeinen Sprachgebrauch oft als Tümpel bezeichnet. Sie sind nur nach Feuchteperioden mit Wasser gefüllt und trocknen sonst aus, z.B. Fahrspuren und Pfützen. Sie sind im Naturpark überall verstreut zu finden.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_naturn_temp_kleingew.jpg" target="_blank" title="naturnahes temporäres Kleingewässer">naturnahes temporäres Kleingewässer';
					break;
				case 26:
					$item['coordinates'] = '50.88996935,14.77808289';
					$item['name'] = 'Röhrricht - Rohrkolben und Schilf am Olbersdorfer See in der Verlandungszone';
					
					$item['popuptext'] = 'In den Verlandungsbereichen von Seen bilden sich typische Gesellschaften aus Schilf, Rohrkolben und Wasserschwaden. Diese Bereiche sind meist wertvoll für diverse Sing- und Schwimmvögel, die auf Deckung angewiesen sind.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_verlandungsroehrichte_osee.jpg" target="_blank" title="Röhrricht - Rohrkolben und Schilf am Olbersdorfer See in der Verlandungszone">Röhrricht - Rohrkolben und Schilf am Olbersdorfer See in der Verlandungszone';
					break;
				case 27:
					$item['coordinates'] = '50.841218,14.740645';
					$item['name'] = '"Streuobstwiese, Almankawiese in Oybin"';
					
					$item['popuptext'] = 'Streuobstwiesen finden sich im ganzen Naturpark verstreut. Es sind Obstbaumbestände aus älteren Bäumen, die auch Totholz und Baumhöhlen aufweisen und extensiv genutzt werden.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_streuobstwiese_oybin.jpg" target="_blank" title="Streuobstwiese, Almankawiese in Oybin ">Streuobstwiese, Almankawiese in Oybin ';
					break;
				case 28:
					$item['coordinates'] = '50.91778,14.730045';
					$item['name'] = 'Trockengebüsch auf den Scheiber Spitzberg mit Halbtrockenrasen';
					
					$item['popuptext'] = 'Trockengebüsche treten oft an südlich ausgerichteten Hängen auf trockenwarmen Standorten auf und sind häufig vergesellschaftet mit Halbtrocken- und Trockenrasen. Kennzeichnende Pflanzenarten sind alle wärmeliebende Arten wie beispielsweise Liguster, Schlehe und Gewöhnliche Berberitze. Auf den kombinierten Halbtrockenrasen treten häufig Fieder-Zenke, Zierliches Schillergras und Feld-Mannstreu auf.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_trockengebuesch_spitzberg.jpg" target="_blank" title="Trockengebüsch auf den Scheiber Spitzberg mit Halbtrockenrasen">Trockengebüsch auf den Scheiber Spitzberg mit Halbtrockenrasen';
					break;
				case 29:
					$item['coordinates'] = '50.881106,14.678063';
					$item['name'] = 'Weiden-Moor- und Sumpfgebüsch';
					
					$item['popuptext'] = 'Weiden-, Moor- und Sumpfgebüsche sind Gebüsche auf nassen, sumpfigen oder moorigen Standorten. Vorherrschend sind Strauchweiden mit breiten Blättern wie etwa Grau-Weide oder Ohr-Weide. Selten spielen auch Kriechweiden auf diesen Standorten eine Rolle.<br></br><a href="http://naturparkblicke.de/images/Bilder/biotop_lrt_weidengebuesch_goldfabianteich.jpg" target="_blank" title="Weiden-Moor- und Sumpfgebüsch">Weiden-Moor- und Sumpfgebüsch';
					break;
				case 30:
					$item['coordinates'] = '50.877484,14.699759';
					$item['name'] = 'Steinrücken';
					
					$item['popuptext'] = 'Steinrücken sind anthropogenen Ursprungs, da sie durch Absammeln größerer Steinbrocken von Landwirtschaftsflächen und gesammelter Ablagerung an den Feldrändern entstanden sind. Sie können linienförmig oder in Kreisform angehäuft sein. Meist sind diese Biotope vegetationsfrei, auf älteren Lesesteinhaufen wachsen dagegen vor allem wärme- und trockenheitsliebende Baumarten.';
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

		for ($i = 1; $i <= 19; $i++) {
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
			
				case 3:
					$item['coordinates'] = '50.825901,14.769347';
					$item['name'] = 'Eibe an der Gabler Straße';
					
					$item['popuptext'] = 'Die Eiben von Lückendorf stocken auf der jüngsten Sandsteinschicht, die im Lückendorfer Gebiet lagert. Die älteste der Eiben soll ein Alter von 1500 Jahren besitzen. Wegen des Häufigkeitsrückgangs steht die Eibe in Deutschland als gefährdet auf der Roten Liste.';
					break;

				case 4:
					$item['coordinates'] = '50.93030526,14.67515945';
					$item['name'] = 'Forstenkuppe';
					
					$item['popuptext'] = 'Auf der Kuppe des Forstenbergs wurde ein Flächennaturdenkmal errichtet, das eine Größe von 3,4 ha aufweist. Das Flächennaturdenkmal ist bewachsen mit einem Bergland-Buchenwald, den es wegen seiner Naturnähe zu schützen gilt.';
					break;

				case 5:
					$item['coordinates'] = '50.82583678999999,14.75775003';
					$item['name'] = 'Hang an der Birkwiese';
					
					$item['popuptext'] = 'Das Flächennaturdenkmal &quot;Hang an der Birkwiese&quot; hat eine Größe von gerade einmal 0,2 ha, bietet aber eine große Artenvielfalt auf der Bergwiese, die mit einer Streuobstwiese verknüpft ist. Die Tier- und Pflanzenwelt ist hier sehr abwechslungsreich und mit eine wenig Glück kann man seltene Arten beobachten.';
					break;

				case 6:
					$item['coordinates'] = '50.89618516,14.72678661';
					$item['name'] = 'Jachelberg';
					
					$item['popuptext'] = 'Auf dem Jachelberg wurde ein Flächennaturdenkmal mit einer Größe von 0,4 Hektar festgesetzt. Schutzwürdig ist die Fläche wegen des besonderen Arteninventars, das für die hier vorhanden Trockengebüsche und Halbtrockenrasen typisch ist.';
					break;

				case 7:
					$item['coordinates'] = '50.8655884,14.69156384';
					$item['name'] = 'Mittlere Wiese an der Poche';
					
					$item['popuptext'] = 'Das Flächennaturdenkmal &quot;Mittlere Wiese an der Poche&quot; hat eine Größe von rund 1,1 Hektar. Eingenommen wird die Fläche von einer Nasswiese, auf der einige typische und zum Teil seltene Arten der Feucht- und Nasswiesen angetroffen werden können.';
					break;

				case 8:
					$item['coordinates'] = '50.834524,14.752033';
					$item['name'] = 'Muschelsaal';
					
					$item['popuptext'] = 'Durch den Kontakt mit vulkanischem Gestein bildeteten sich auf dem Sandstein innerhalb der Großen Felsengasse zahlreiche Bänder, Wülste und Ringe, die den Betrachter an die Form von Muscheln erinnern. Diese Formen sind aus Brauneisenerzausfällungen aufgebaut. Das Gestein des vulkanischen Gangs ist inzwischen verwittert, wodurch die Felsengasse freigelegt wurde.';
					break;

				case 9:
					$item['coordinates'] = '50.92420552,14.68297005';
					$item['name'] = 'Neuwiese';
					
					$item['popuptext'] = 'Die Neuwiese in Hainewalde trägt ihren Namen nicht zu unrecht. Das 0,2 Hektar große Flächennaturdenkmal befindet sich mitten im Waldgebiet &quot;Hofebusch&quot; und zeichnet sich durch eine artenreiche Feuchtwiese aus.';
					break;

				case 10:
					$item['coordinates'] = '50.84459906,14.7413671';
					$item['name'] = 'Nordwand des Oybins';
					
					$item['popuptext'] = 'Auf der Nordseite des Berg Oybin wurde eine Flächennaturdenkmal mit einer Größe von 4,3 Hektar festgesetzt. Das Naturdenkmal ist dabei relativ inhomogen, denn zur Fläche gehören sowohl die Felsformationen und der Kiefernwald auf der östlichen Seite des Berg Oybin, als auch ein Teich mit Erlen-Eschenwald am nördlichen Fuß des Oybin.';
					break;

				case 11:
					$item['coordinates'] = '50.89713253,14.77543116';
					$item['name'] = 'Pethauer Teich im Westpark';
					
					$item['popuptext'] = 'Der Pethauer Teich befindet sich im Westpark, benachbart liegt ein Stadion. Das Gebiet des Teiches und seines Ufers wurde zum Flächennaturdenkmal erklärt, welches eine Größe von etwa 1,5 Hektar aufweist. Unter Anglern erfreut sich der Teich großer Beliebtheit.';
					break;

				case 12:
					$item['coordinates'] = '50.92657248,14.62825298';
					$item['name'] = 'Polierschieferhalden Seifhennersdorf';
					
					$item['popuptext'] = 'In den Polierschieferhalden von Seifhennersdorf lagert das Sedimentgestein Polierschiefer, welcher ein feines, wassersaugendes Silikatgestein ist und auf dem Wasser schwimmt (Schwimmschiefer). Er entstand als Ablagerung in Süßwasserseen während der Tertiärzeit und besteht im Grunde aus abgestorbenen Kieselalgen.';
					break;

				case 13:
					$item['coordinates'] = '50.92800612000001,14.56941605';
					$item['name'] = 'Quellhang südlich des Frenzelsberges';
					
					$item['popuptext'] = 'Das Flächennaturdenkmal südlich des Frenzelsbergs weist eine Größe von 1,6 ha auf. Da es sich um einen Quellhang handelt, ist der gesamte Bereich feucht bis nass, weshalb sich an dieser Stelle eine artenreiche Nasswiese ausgebildet hat.';
					break;

				case 14:
					$item['coordinates'] = '50.91772621,14.73054171';
					$item['name'] = 'Scheiber Spitzberg';
					
					$item['popuptext'] = 'Bei Mittelherwigsdorf befindet sich der aus Basalt aufgebaute Spitzberg, dessen Kuppe auf einer Fläche von 2,9 Hektar zum Flächennaturdenkmal ausgewiesen wurde. Die Fläche wird eingenommen von einer abwechslungsreichen Mischung aus Trockengebüschen, Halbtrockenrasen und Laubwald trockener Standorte (vor allem Eichen).';
					break;

				case 15:
					$item['coordinates'] = '50.84582522000001,14.69480395';
					$item['name'] = 'Schwarzes Loch - ehemaliges Bergwerk';
					
					$item['popuptext'] = 'Als "Schwarzes Loch" wird der größte der vier Mühlsteinbrüche bezeichnet. Im Inneren wurde für die Besucher eine Schauwerkstatt eingerichtet.<br></br><a href="sonstiges_geotop_schwarzes_loch.jpg" target="_blank" title="Schwarzes Loch - ehemaliges Bergwerk">Schwarzes Loch - ehemaliges Bergwerk</a>';
					break;

				case 16:
					$item['coordinates'] = '50.93175231,14.63340282';
					$item['name'] = 'Südwesthang des Richterberges';
					
					$item['popuptext'] = 'Am Südwesthang des Richterbergs befindet sich ein Flächennaturdenkmal, das eine Größe von 1,3 Hektar aufweist. Wegen der exponierten Lage am Südwesthangs des Hügels, haben sich hier trockene Lebensräume ausgebildet. Die Fläche wird folglich geprägt durch artenreiche Halbtrockenrasen und Trockengebüsche.';
					break;

				case 17:
					$item['coordinates'] = '50.88049668,14.68957901';
					$item['name'] = 'Waldgebiet am Pochebach';
					
					$item['popuptext'] = 'Am Pochebach befindet sich das 3,9 Hektar große Flächennaturdenkmal &quot;Waldgebiet am Pochebach&quot;. In diesem Bereich ist die Poche, der schmale Bach zwischen Jonsdorf und Bertsdorf, naturnah entwickelt und an den Ufern stockt ein Erlen-Eschenwald. Im östlichen Bereich des Flächennaturdenkmals wächst zudem ein feuchter Eichen-Hainbuchenwald, wodurch ein abwechslungsreiches Landschaftsgebiet entsteht.';
					break;

				case 18:
					$item['coordinates'] = '50.94292146,14.64741468';
					$item['name'] = 'Wiese am Grenzfischelgraben';
					
					$item['popuptext'] = 'Die nur 0,3 Hektar große &quot;Wiese am Grenzfischelgraben&quot; wurde als Flächennaturdenkmal ausgewiesen. Hier kann der aufmerksame Beobachter eine artenreiche Nasswiese finden, die den typischen Arten dieses Lebensraums ein Habitat bietet.';
					break;

				case 19:
					$item['coordinates'] = '50.91161806,14.66364741';
					$item['name'] = 'Wiese an den Hofefeldern';
					
					$item['popuptext'] = 'Das Flächennaturdenkmal &quot;Wiese auf den Hofefeldern&quot; erreicht eine Größe von etwa 0,4 Hektar. Die Fläche ist geprägt durch eine artenreiche Nasswiese und Auengebüsch.';
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
				case 3:
					$item['coordinates'] = '50.87039586000001,14.8237967491149';
					$item['name'] = 'Dreiländereck(punkt)';
					$item['popuptext'] = 'Hier treffen sich die Grenzen der drei Länder Deutschland, Polen und Tschechien. Die Grenzen werden markiert durch die Lausitzer Neiße zwischen Deutschland und Tschechien und Polen und dem Bach Oldrichovský potok zwischen Polen und Tschechien. Wo der Bach in die Neiße mündet, befindet sich der Dreiländerpunkt. Folgt man dem Neißeweg, überquert die Grenze bei Hartau und geht den Neißeweg auf der tschechischen Seite, kann man so an einem Tag drei Länder queren.';
					break;
				case 4:
					$item['coordinates'] = '0,0';
					$item['name'] = 'Eiszeitgedenkstein';
					$item['popuptext'] = 'Der Eiszeitgedenkstein in Oybin erinnert an die maximale Ausdehnung des Eisschildes zur Elstereiszeit vor 320.000 Jahren.';
					break;
				case 5:
					$item['coordinates'] = '50.84865679,14.7038054466247';
					$item['name'] = 'Gebirsbad Jonsdorf';
					$item['popuptext'] = 'Im Gebirgsbad in Jonsdorf kann man in den Monaten Juli und August nach den Wanderungen einen Sprung ins kühle Nass machen. Das Wasser des Gebirgsbades wird solarbeheizt, sodass trotz der Lage im Gebirge angenehme Wassertemperaturen erreicht werden. Auch ein Tennisplatz, Spielplatz und ein Basketballplatz sind vorhanden.';
					break;
				case 6:
					$item['coordinates'] = '50.89826257,14.69173551';
					$item['name'] = 'Himmelsbrücke Hainewalde';
					$item['popuptext'] = 'Da die vorher vorhandene Holzbrücke den unterschiedlichen Wasserführungslinien der Mandau nicht standhalten konnte, wurde 1732 eine steinerne Brücke an dessen Stelle errichtet. Heute ist die Brücke eine Wahrzeichen von Hainewalde.';
					break;
				case 7:
					$item['coordinates'] = '50.84867711,14.68337774';
					$item['name'] = 'Jonsdorfer Klettersteig';
					$item['popuptext'] = 'Der Klettersteig an den Nonnenfelsen wurde 1994 eröffnet und hat eine Länge von 400 m, wobei 100 Höhenmeter überwunden werden. Besondere Attraktion des Steigs ist eine neun Meter lange Hängebrücke. Klettersteigausrüstung ist zum Begehen absolut erforderlich.';
					break;
				case 8:
					$item['coordinates'] = '50.83459888,14.75078702';
					$item['name'] = 'Klettersteig &quot;Alpiner Grat&quot;';
					$item['popuptext'] = 'Der Klettersteig &quot;Alpiner Grat&quot; führt hinauf zum gleichnamigen Felsen, der sich in der Oybiner Felsengasse befindet. Auf einer Länge von fast 100 Metern werden 64 Höhenmeter überwunden. Klettersteigausrüstung ist zum Begehen des schweren Steigs unbedingt erforderlich.';
					break;
				case 9:
					$item['coordinates'] = '50.85291736,14.69986796';
					$item['name'] = 'Sternwarte';
					$item['popuptext'] = 'Die Sternwarte in Jonsdorf wurde in Eigeninitiative von einigen wenigen begeisterten Hobbyastronomen aufgebaut. Jährlich von Mai bis Oktober finden hier immer donnerstags Treffen und Führungen statt. Zudem können private Gruppenführungen gebucht werden.';
					break;
				case 10:
					$item['coordinates'] = '50.88155266999999,14.67003107';
					$item['name'] = 'Trixi-Park';
					$item['popuptext'] = 'Der Trixi-Park bietet ganzjährig Spaß für die ganze Familie im angeschlossenen Freizeitbad und Wellnesscenter. Zum Trixi-Park gehört auch ein Campingplatz mit Ferienhäusern. Regelmäßig finden dort Veranstaltungen statt wie etwa geführte Wanderungen und Radwanderungen.';
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

		for ($i = 1; $i <= 38; $i++) {
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
				case 3:
					$item['coordinates'] = '50.85995424,14.71395493';
					$item['name'] = 'Kurpark Jonsdorf';
					
					$item['popuptext'] = 'Der Kurpark ins Jonsdorf ist an ganz an den Bedürfnissen der Erholung ausgerichet. Unter anderem findet der Besucher hier ein Wassertretbecken und einen Teich. Im benachbarten Cafe finden regelmäßig Veranstaltungen statt.';
					break;
				case 4:
					$item['coordinates'] = '50.85528117,14.70476031';
					$item['name'] = 'Schmetterlingshaus';
					
					$item['popuptext'] = 'Im 2004 eingeweihten Schmetterlingshaus kann man ganzjährig über 200 verschiedene Schmetterlingsarten sowie zahlreiche Reptilien und Spinnen sehen. Auch ein Seewasseraquarium mit einer Länge von 3 Metern wurde dort eingerichtet und läd zur Besichtigung ein.';
					break;

				case 5:
					$item['coordinates'] = '50.86107842,14.71644402';
					$item['name'] = 'Freizeit- und Eissporthalle Jonsdorf';
					
					$item['popuptext'] = 'In der Eissporthalle Jonsdorf kann man in den Monaten von August bis April auf einer Fläche von 1800 Quadratmeter Eislaufen. Die benötigten Schlittschuhe können auch ausgeliehen werden. In den Sommermonaten finden hier verschiedene Konzerte und Veranstaltungen statt. Auch eine Kletterwand mit einer Höhe von 14 m kann hier erklommen werden.';
					break;

				case 6:
					$item['coordinates'] = '50.85544372,14.692626';
					$item['name'] = 'Gondelfahrt Jonsdorf';
					
					$item['popuptext'] = 'Das  &quot;Gondelfahrt&quot; ist ein traditionsreiches Hotel, dessen Tradition bis ins 18. Jahrhundert zurückgeht. Nicht nur Zimmer kann man hier mieten, sondern auch in der Bauernstube in beschaulicher Atmosphäre unterhalb der Nonnenfelsen einkehren.';
					break;

				case 7:
					$item['coordinates'] = '50.89659118,14.81167316';
					$item['name'] = 'Krokuswiese Zittau ?';
					
					$item['popuptext'] = 'Am Stadtring unweit der Blumenuhr liegt die Zittauer Krokuswiese, auf der jedes Jahr im Frühjahr viele blühende Krokusse das Auge erfreuen.';
					break;

				case 8:
					$item['coordinates'] = '50.90084737,14.83264804';
					$item['name'] = 'Tierpark Zittau ?';
					
					$item['popuptext'] = 'Der Tierpark in Zittau wurde 1956 gegründet und ist ganzjährig geöffnet (im Winter verkürzte Öffnungszeiten). Zu sehen gibt es hier u. a. Luchse, Rotwild, Känguruhs, Pinguine und Nasenbären. Auch finden regelmäßig verschiedene Aktionen statt, z. B. Märchennacht und Tierparkfest.';
					break;

				case 9:
					$item['coordinates'] = '50.89573854,14.81225789';
					$item['name'] = 'Blumenuhr Zittau ?';
					
					$item['popuptext'] = 'Am Karl-Liebknecht-Ring befindet sich die Zittauer Blumenuhr, eine Besonderheit der Stadt. Es handelt sich um ein Uhrwerk aus einer alten Turmuhr, das in mehrere Blumenbeete eingebettet ist. Zu jeder halben und vollen Stunde ertönt zudem ein bekanntes Volkslied.';
					break;

				case 10:
					$item['coordinates'] = '50.86905524,14.65004325';
					$item['name'] = 'Naturparkhaus Waltersdorf';
					
					$item['popuptext'] = 'Das Naturparkhaus wird in einem historisch wertvollen Gebäude erricht, dem Waltersdorfer &quot;Kretscham&quot;, welcher dem deutschen Wort &quot;Erbgericht&quot; entsprechend früher das Zentrum des Bergortes darstellte. Lange Zeit war das Gebäude dem Verfall preisgegeben, bevor sich ein Verein erfolgreich für dessen Renovierung einsetzte. Heute befindet sich hier die Touristinformation und zukünftig wird eine Dauerausstellung im Naturparkhaus über den Naturpark informieren.';
					break;

				case 11:
					$item['coordinates'] = '50.85317499999999,14.695981';
					$item['name'] = 'Oberlausitzer Bauerngarten';
					
					$item['popuptext'] = 'Der Jonsdorfer Bauerngarten zeigt die typischen Pflanzenwelt eines Oberlausitzer Bauerngartens, neben Kräutern und Gewürzen werden hier auch zahlreiche Nautzpflanzen wie Flachs angebaut. Er wurde 2001 von der Gemeinde Jonsdorf und der Naturschutzbehörde eingerichtet und soll an alte Traditionen der hiesigen Bauern erinnern. Neben Blumen und Kräutern, gibt es auch Obstbäume, Sträucher, Hecken und auch eine Teichlandschaft zu sehen.';
					break;

				case 12:
					$item['coordinates'] = '50.85360823,14.69756126';
					$item['name'] = 'Weberstube Jonsdorf';
					
					$item['popuptext'] = 'Die Weberstube in Jonsdorf versteht sich als Schauwerkstatt und Museum, in dem die Oberlausitzer Tradition der Leinenweberei, die Bauweise von Umgebindehäusern und die Lebensweise der damaligen Landbebölkerung vorgestellt werden. Auch ein Geschäft befindet sich im Haus, wo man typische Weberzeugnisse erwerben kann.';
					break;

				case 13:
					$item['coordinates'] = '50.925308,14.744777';
					$item['name'] = 'Katzenlehne Mittelherwigsdorf';
					
					$item['popuptext'] = 'Bei der Katzenlehne handelt es sich um eine Streuobstwiese, die mit einem Magerrasen verbunden ist. Sie liegt hängig am Mandauradweg und bietet vor allem in den warmen Monaten eine stimmungsvolle Atmosphäre.';
					break;

				case 14:
					$item['coordinates'] = '50.85105468,14.73790169';
					$item['name'] = 'Katzenkerbe';
					
					$item['popuptext'] = 'Die Katzenkerbe ist ein auffälliger Felsendurchbruch, der vom Wanderweg zwischen Ameisenberg und der Alten Leipaer Straße bzw. Oybin und der alten Handelsstraße. An den Wänden kann man noch Werkzeugspuren finden, was ein Indiz dafür ist, das dieser Durchbruch von Menschen angelegt wurde. Wahrscheinlich sollte so der Weg zwischen dem Berg Oybin und der Leipaer Straße verkürzt werden.';
					break;

				case 15:
					$item['coordinates'] = '50.91320775,14.75212812';
					$item['name'] = 'Barfußweg Mittelherwigsdorf';
					
					$item['popuptext'] = 'Der Barfußweg hat eine Länge von 2,5 km und führt durch das landschaftlich schöne Schülertal entlang der Mandau. Dabei wird auch der Schülerbusch durchquert, ein Flächennaturdenkmal, das teilweise von Labkraut-Eichen-Hainbuchenwald eingenommen wird. Auf dem Weg liegen 14 Stationen, an denen der Geher verschiedene Untergründe fühlen oder Herausforderungen absolvieren kann. Auf dem Rückweg kurz vor dem Ziel (Sportplatz) wird auch der Fluss Mandau gequert.';
					break;

				case 16:
					$item['coordinates'] = '50.85009283,14.73159313';
					$item['name'] = 'Alte Leipaer Straße';
					
					$item['popuptext'] = 'Die Alte Leipaer Straße ist eine alte Handelsstraße, die zwischen Ameisenberg und Jonsberg hindurchführt und Olbersdorf mit der Kreuzung &quot;Stern&quot; verbindet. An dieser Kreuzung zwischen dem Oybiner Ortsteil Hain und Jonsdorf treffen sich zahlreiche Wege aus unterschiedlichen Richtungen, sodass die Form eines Sternes entsteht. Entlang der Alten Leipaer Straße wurde ein 3,5 km langer Waldlehrpfad eingerichtet.';
					break;

				case 17:
					$item['coordinates'] = '50.84176047,14.79227543';
					$item['name'] = 'Alte Gabler Straße';
					
					$item['popuptext'] = 'Die Alte Gabler Straße war im Mittelalter eine bedeutende Handelsstraße Richtung Böhmen. Sie führte von Eichgraben zum Forsthaus in Lückendorf weiter nach Böhmen. Zum Schutz der Händler und Eintreiben von Zöllen wurde hier im Jahr 1357 die Burg Karlsfried gebaut.';
					break;

				case 18:
					$item['coordinates'] = '50.89967299999999,14.66686';
					$item['name'] = 'Naturlehrpfad, Großschönau, Streuobstwiesen';
					
					$item['popuptext'] = 'Im Rahmen eines gemeinsamen Projektes mit der Stadt Hradek nad Nisou errichtete der Landschaftspflegeverband Zittauer Gebirge und Vorland e.V. 2010 in Großschönau einen Streuobstlehrpfad. Auf 25 Tafeln erfahren die Besucher Informatives über historische Apfelsorten, Streuobstwiesen sowie zur Fauna und Flora dieses geschützten Lebensraumes.';
					break;

				case 19:
					$item['coordinates'] = '50.90768759000001,14.75850642';
					$item['name'] = 'Naturlehrpfad &quot;Schülerbusch&quot;';
					
					$item['popuptext'] = 'Der rund 2 km lange Lehrpfad durch den Schülerbusch informiert ausgehend vom Ökozentrum auf 13 Infotafeln über geologische, morphologische und kulturhistorische Besonderheiten des Schülertals und seiner Umgebung. Dabei werden unter anderem der Quellbereich des Schülerbachs, die Mandau-Terrassen und der untere Steinbruch gequert, wo die bis zu 35 m hohen Phonolithsäulen aufragen.';
					break;

				case 20:
					$item['coordinates'] = '50.89403321,14.77197647';
					$item['name'] = 'Naturlehrpfad &quot;Olbersdorfer Tagebau&quot;';
					
					$item['popuptext'] = 'Auf dem rund 10 km langen Lehrpfad rund um den Olbersdorfer See und die durch den Kohleabbau geschaffene  Althalde können zahlreiche Relikte aus der Zeit des Braunkohleabbaus vorgefunden werden. Zugleich kann man sich auf den etwa 40 Lehrtafeln über die Rekultivierung dieser Bergbaulandschaft informieren. Für Rollstuhlfahrer gibt es eine kürzere Variante um den Olbersdorfer See';
					break;

				case 21:
					$item['coordinates'] = '50.849228,14.699296';
					$item['name'] = 'Naturlehrpfad Jonsdorfer &quot;Mühlsteinbrüche&quot;';
					
					$item['popuptext'] = 'Durch die Mühlsteinbrüche führt ein Naturlehrpfad, der bereits in den 1950er Jahren angelegt wurde. Auf den Hinweistafeln entlang des 2 bis 3stündigen Weges werden dem Besucher die Bedeutung und Vorgehensweise des Mühlsteinabbaus sowie geologische Besonderheiten anschaulich näher gebracht.';
					break;

				case 22:
					$item['coordinates'] = '50.85918897000001,14.65476394';
					$item['name'] = 'Naturlehrpfad &quot;Lebensräume&quot; in Waltersdorf';
					
					$item['popuptext'] = 'Der Naturlehrpfad &quot;Lebensräume im Naturpark&quot; klärt auf 15 Lehrtafeln über die Lebensräume und typische Tier- und Pflanzenarten auf. Er führt ausgehend von Waltersdorf in die abwechslungsreiche Landschaft rund um die Lausche. Auch die Lausche selbst wird dabei erklommen.';
					break;

				case 23:
					$item['coordinates'] = '50.87766704,14.65380907';
					$item['name'] = 'Denkmalpfad Waltersdorf';
					
					$item['popuptext'] = 'Durch den Erholungsort Waltersdorf führt auf etwa 3 km Länge der Denkmalspfad, der die Waltersdorfer Tradition der Umgebindehäuser näher darstellt. Dazu wurden entlang der Wegstrecke durch den Ort 10 Schautafeln aufgestellt.';
					break;

				case 24:
					$item['coordinates'] = '50.90016397,14.75961685';
					$item['name'] = 'Natur- und Kulturlehrpfad Bertsdorf-Hörnitz';
					
					$item['popuptext'] = 'Auf 13 Informationstafeln kann sich der Besucher auf diesem Lehrpfad über die Sehenswürdigkeiten und einige Besonderheiten des Ortes Bertsdorf-Hörnitz informieren. Entlang des Weges sieht man u. a. die neugotische Hörnitzer Kirche, zahlreiche Umgebindehäuser und die Bertsdorfer Kirche.';
					break;

				case 25:
					$item['coordinates'] = '50.89607689,14.78382111';
					$item['name'] = 'Lehrpfad &quot;Auf den Spuren des Bergbaus&quot;';
					
					$item['popuptext'] = 'Mit einer Länge von 15 km eignet sich dieser Lehrpfad über die Vergangenheit des Altbergbaus im Zittauer Becken gut für eine Radwanderung. Auf 27 Lehrtafeln kann man sich über den Bergbau im Gebiet informieren und zahlreiche Relikte der ehemaligen Bergbatätigkeit sehen.';
					break;

				case 26:
					$item['coordinates'] = '50.84939800000001,14.696362';
					$item['name'] = 'Waldbühne Jonsdorf';
					
					$item['popuptext'] = 'Die Waldbühne in Jonsdorf, unterhalb des Carolafelsens gelegen, wurde 1953 eingeweiht und seitdem mehrmals modernisiert. Sie bietet im Sommer Freiluftaufführungen von Theaterstücken, Kinofilmen sowie verschiedene musikalische Veranstaltungen.';
					break;

				case 27:
					$item['coordinates'] = '50.86844585,14.74684954';
					$item['name'] = 'Schmalspurbahn';
					
					$item['popuptext'] = 'Seit dem 25. November 1890 verkehrt die Schmalspurbahn mit einer Spurweite von 750 mm zwischen der Stadt Zittau und den Orten Jonsdorf und Oybin. Auf ihrem Weg von Zittau nach Jonsdorf legt die Bahn dabei einen Höhenunterschied von 165 m zurück. Zum Einsatz kommen auf der Strecke Dampflokomotiven und restaurierte Personenwagen.';
					break;

				case 28:
					$item['coordinates'] = '50.827215,14.801226';
					$item['name'] = 'Böhmisches Tor';
					
					$item['popuptext'] = 'Das Böhmische Tor ist ein markanter Sandsteinfelsen im Weißbachtal, der unweit des Wanderweges liegt. Seine Sandsteinschichten sind schief gestellt, was auf eine ungleichmäßige Hebung infolge der Lausitzer Überschiebung hindeutet. Er ist ein beliebter Kletterfelsen.';
					break;

				case 29:
					$item['coordinates'] = '50.84387418,14.74112034';
					$item['name'] = 'Burg Oybin';
					
					$item['popuptext'] = 'Im 13. Jahrhundert wurde zum ersten Mal eine Burg auf dem dafür wegen seiner Steilwände gut geeigneten Berg Oybin errichtet. Bereits 1280 wurde diese zerstört. Als Kaiser Karl IV. die Burg in Besitz nahm, siedelte er auf der neu verstärkten Burg Mönche an (Cölestinermönche), die bis zur Reformation um 1540 die Burg bewohnten. 1577 kam es zu einem Brand, der einen Großteil des Bauwerks zerstörte.';
					break;

				case 30:
					$item['coordinates'] = '50.83472762,14.79239345';
					$item['name'] = 'Burgruine Karlsfried';
					
					$item['popuptext'] = 'Die Burg Karlsfried wurde im 14. Jahrhundert an der Alten Gabler Straße, der wichtigsten Handelsstraße nach Böhmen, errichtet. Sie diente zum Schutz der Handelsstraße und der Erhebung von Wegezoll. 1424 wurde die Burg durch die Hussiten zerstört, danach jedoch erneut aufgebaut. Da sich hiernach ein Raubritter ansiedelte, wurde Karlsfried vom Sechsstädebund aufgekauft und im Jahr 1443 zerstört und damit zur Burgruine.';
					break;

				case 31:
					$item['coordinates'] = '50.834477,14.751964';
					$item['name'] = 'Große und kleine Felsengasse Oybin';
					
					$item['popuptext'] = 'Die große und die kleine Felsengasse sind zwei Wege, die jeweils rechts und links durch Felsen aus Sandstein begrenzt werden. Die große Felsengasse selbst stellt eine Art Grenze dar zwischen dem harten Sandstein von Oybin und Töpfer und dem weichen Sandstein im Gebiet um Lückendorf. Im &quot;Muschelsaal&quot;, einem Bereich der großen Felsengasse, können auffällige Vererzungen mit Brauneisen besichtigt werden, die auf vulkanische Tätigkeit zurückgehen.';
					break;

				case 32:
					$item['coordinates'] = '50.911628,14.703693';
					$item['name'] = 'Kanitz-Kyawsche Gruft, Hainewalde';
					
					$item['popuptext'] = 'Die Gruftkapelle des Adelsgeschlechts Kanitz-Kyaw wurde 1715 errichtet und stellt ein sehr bedeutendes barockes Grabgewölbe in der Oberlausitz dar. Sie wurde aus Sandstein der Region erbaut und wird von zahlreichen kunstvollen Skulpturen geziert, die in den Sandstein geschlagen wurden.';
					break;

				case 33:
					$item['coordinates'] = '50.91518299999999,14.707808';
					$item['name'] = 'Hainewalder Schloss';
					
					$item['popuptext'] = 'Das Schloss in Hainewalde wurde in den Jahren 1749 bis 1755 im Baustil des Barock erbaut. Diese barocken Elemente verlor das Schloss 1833, als es umgebaut wurde. Bis 1927 wurde das Schloss von Adligen des Geschlechts Kanitz-Kyaw bewohnt, die das Gebäude wegen Überschuldung verkaufen mussten. Seit 1972 steht es leer und kann nur von außen besichtigt werden.';
					break;

				case 34:
					$item['coordinates'] = '50.93507,14.60913';
					
					$item['name'] = 'Karasekmuseum Seifhennersdorf';
					$item['popuptext'] = 'Das Karasekmuseum in Seifhennersdorf ist ein Volkskundemuseum, in dem viele Einblicke in das frühere Leben der Oberlausitzer gegeben werden. Namesgeber für das Museum war der Räuberhauptmann Karasek, der im 18. Jahrhundert für seine Raubzüge berüchtigt war. Im Museum wird sein Leben und Wirken vorgestellt.';
					break;

				case 35:
					$item['coordinates'] = '50.84669232,14.6973466873';
					$item['name'] = 'Mühlsteinbrüche';
					
					$item['popuptext'] = 'Zu den Mühlsteinbrüchen gehören vier ehemalige Steinbrüche, in denen der für Mühlsteine besonders geeignete vulkanisch überprägte Sandstein abgebaut wurde. Erstmals wurden im Jahr 1850 im Bärloch Mühlsteine abgebaut. Der größte der vier Steinbrüche ist das &quot;Schwarze Loch&quot;. Zu besichtigen sind die Mühlsteinbrüche über einen Naturlehrpfad, der durch das Gebiet führt.';
					break;

				case 36:
					$item['coordinates'] = '50.8563648,14.657714366';
					$item['name'] = 'Roter Steinbruch am Sonneberg';
					
					$item['popuptext'] = 'Am Westhang des Sonnenbergs befindet sich der ehemalige Steinbruch, in dem bis ins 20. Jahrhundert hinein Sandstein abgebaut wurde. Dieser war für die Herstellung von Mühlsteinen nicht geeignet, fand aber Verwendung für Türstöcke und Grabsteine. Wegen der rötlichen Farbe des Sandsteins wird er als &quot;Roter Steinbruch&quot; bezeichnet.';
					break;

				case 37:
					$item['coordinates'] = '50.85430586,14.7527289390563';
					
					$item['name'] = 'Teufelsmühle';
					$item['popuptext'] = 'Die Teufelsmühle ist die älteste Mühle im Naturpark. Sie liegt am Goldbach und stammt aus der Besiedlungszeit der Cölestinermönche. Heute beherbergt die Mühle eine Gaststätte, die schon wegen der schönen Lage empfehlenswert ist.';
					break;

				case 38:
					$item['coordinates'] = '50.868982,14.6488839';
					$item['name'] = 'Volkskunde und Mühlenmuseum Waltersdorf';
					
					$item['popuptext'] = 'Das Volkskunde- und Mühlenmuseum Waltersdorf wurde in der ältesten Mühle des Ortes 1956 durch Alfred Jungmichel ins Leben gerufen. Es bietet eine interessante Ausstellung über die Geschichte der Müllerei, des Ortes Waltersdorf und die Lebensbedingungen der Weber im 19. Jahrhundert.';
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

		for ($i = 1; $i <= 20; $i++) {
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
				case 3:
					$item['coordinates'] = '50.897784,14.662505';
					$item['name'] = 'Großschönau';
					
					$item['popuptext'] = 'Der Name des im Mandautal gelegenen Ortes  bedeutet Schöne Aue. Die Vorsilbe Groß diente wohl zur Unterscheidung von Kleinschönau, dem heutigen Sieniawka. Die Gründung erfolgte im 12. Jh. mit dem Anlegen eines Waldhufendorfes. Im Unterschied zu anderen Waldhufen findet man hier noch zusammenhängende Waldflächen. Als einer der bevölkerungsreichsten Orte der Oberlausitz zählt Großschönau 4779 Einwohner (Stand  12.2009).';
					break;
				case 4:
					$item['coordinates'] = '50.83447013,14.72146511';
					$item['name'] = 'Hain';
					
					$item['popuptext'] = 'Hain ist ein Ortsteil von Oybin und erst Mitte des 16. Jh. durch das Ansiedeln von 4 Gärtnern entstanden. Im Laufe der Jahre entstand mit dem Zuzug weiterer Menschen ein Häuslerreihendorf. Die Einwohner verdienten sich ihren Lebensunterhalt  mit Waldarbeit, Steinbrecherei und Hausweberei, bis im 19. Jh. der Tourismus an Bedeutung gewann. Von Hain eröffnet sich eine sehr schöne Aussicht auf das Gebirge bis zum Zittauer Becken.';
					break;
				case 5:
					$item['coordinates'] = '50.911635,14.7024601';
					$item['name'] = 'Hainewalde';
					
					$item['popuptext'] = 'Hainewalde wurde bereits im 14. Jahrhundert gegründet. Im Zentrum der Ortschaft mit etwa 1700 Einwohnern befindet sich das Schloss Hainewalde, das zwischen 1749 und 1755 erbaut wurde und bis 1927 von Adligen bewohnt wurde. Diese überschuldeten sich jedoch und mussten das Schloss an die Gemeinde verkaufen. Heute steht das Schloss leer und kann wegen des baufälligen Zustands auch nicht von innen besichtigt werden.';
					break;
				case 6:
					$item['coordinates'] = '50.8662959,14.7237135';
					$item['name'] = 'Hänischmühe';
					
					$item['popuptext'] = 'Das am Grundbach gelegene Hähnischmühe ist ein Ortsteil der Gemeinde Jonsdorf und wurde im Jahr 1772 durch Johann Gottlieb Hänisch, einem Jonsdorfer Bleichermeister, gegründet. Dieser errichtete auf dem Areal einen Bleichbetrieb mit dazugehörigen Wohnhütten für die Beschäftigten.  Seinen Namen erhielt Hänischmühe jedoch erst 1840. 1890 wurde der Weiler mit der Gründung der Schmalspurbahn auch für Touristen zugänglich gemacht.';
					break;
				case 7:
					$item['coordinates'] = '50.862104,14.814386';
					$item['name'] = 'Hartau';
					
					$item['popuptext'] = 'Der Name Hartau leitet sich vom althochdeutschen Wort harth = Gebirgswald ab. Der nach Zittau eingemeindete Ort besteht aus zwei unterschiedlich entstandenen Teilen. Alt-Hartau, ein altes Straßendorf, wurde bereits im 13. Jh. gegründet. Neu-Hartau ist eine Bergarbeitersiedlung, die im Jahr 1725 entstand. Heute leben hier 558 Menschen (Stand 1.2011).';
					break;
				case 8:
					$item['coordinates'] = '50.8402758,14.7337387';
					$item['name'] = 'Hölle';
					
					$item['popuptext'] = 'Der Ortsteil von Oybin ist nicht so schaurig, wie es der Name vermuten lässt. Im Gegenteil: Etwa 40 historische Fachwerkhäuser sind hier zu sehen, in denen früher die häusliche Weberei betrieben wurde.';
					break;
				case 9:
					$item['coordinates'] = '50.9010701,14.7433189';
					$item['name'] = 'Hörnitz';
					
					$item['popuptext'] = 'Hörnitz ist heute ein Gemeindeteil von Bertsdorf-Hörnitz, das 1994 aus beiden Gemeinden entstand. Im Jahr 1912 wurde der Ort Hörnitz aus den bis dahin bestehenden Orten Alt-Hörnitz und Neu-Hörnitz gebildet.';
					break;
				case 10:
					$item['coordinates'] = '50.8562514,14.7082102';
					$item['name'] = 'Jonsdorf';
					$item['popuptext'] = 'Die erste urkundliche Erwähnung Jonsdorfs ist auf das Jahr 1539 datiert. Es wurde als Waldhufendorf angelegt. 1580 wurde in den heutigen Mühlsteinbrüchen der erste Steinbruch eröffnet. Der Abbau des Sandsteins wurde erst 1917 wieder eingestellt. Mitte des 19. Jh. gewann der Ort zunehmend an touristischer Bedeutung und ist heute ein staatlich anerkannter Luftkurort. Aktuell leben in Jonsdorf 1782 Menschen (Stand 2009)';
					
					break;
				case 11:
					$item['coordinates'] = '50.8317481,14.7580121';
					$item['name'] = 'Lückendorf';
					$item['popuptext'] = 'Lückendorf ist der einzige deutsche Ort auf der Südseite des Gebirges und ca. um 1300 an einer der ältesten Handelsstraßen Europas (der Gabler-Straße) als Waldhufendorf entstanden. Bereits um 1900 erlangte der Ort aufgrund seiner guten Luft touristische Bedeutung. 1971 wurde Lückendorf zum staatlich anerkannten Erholungsort ernannt. 1994 wurde der Ort nach Oybin eingemeindet und verlor seine Selbstständigkeit';
					
					break;
				case 12:
					$item['coordinates'] = '50.9160712,14.7608498';
					$item['name'] = 'Mittelherwigsdorf';
					$item['popuptext'] = 'Die Ortschaft Mittelherwigsdorf wurde im Jahr 1312 zum ersten Mal geschichtlich erwähnt. Vom 15. Jahrhundert bis zur Reformation gehörte der Ort den Mönchen auf der Burg Oybin, danach musste die Mönche wesentliche Bestandteile verkaufen. Prägend für das Stadtbild sind die zahlreichen Umgebindehäuser, die erbaut wurden, als die Leinenweberei im späten 17. Jahrhundert sehr weit verbreitet war und Weber  günstig Bauland erwerben konnten.';
					break;
				case 13:
					$item['coordinates'] = '50.8697227,14.766345';
					$item['name'] = 'Olbersdorf';
					$item['popuptext'] = 'Der Ort Olbersdorf mit etwa 5500 Einwohnern ist besonders bekannt wegen des Olbersdorfer Sees, der im Niederdorf des Ortes zu finden ist.  Gegründet wurde das Dorf Anfang des 14. Jahrhundert von einem Lokator, der vermutlich Albert hieß, denn zuerst wurde der Ort als "Albertsdorf" urkundlich erwähnt. Erst 1430 wird der Name Olbersdorf erstmals erwähnt.';
					break;
				case 14:
					$item['coordinates'] = '50.8417219,14.7430749';
					$item['name'] = 'Oybin';
					$item['popuptext'] = 'Zu den touristisch attraktivsten Orten des Naturparks zählt der Kurort Oybin mit seinen Ortsteilen Hain, Hölle und Lückendorf. Es liegt mitten im Zittauer Gebirge in einem romantischen Tal, das vom Goldbach durchflossen wird. Von hier aus sind viele interessante Ziele leicht zu erreichen.';
					break;
				case 15:
					$item['coordinates'] = '50.9011418,14.7656802';
					$item['name'] = 'Pethau';
					$item['popuptext'] = 'Pethau war bis 1970 eine eigentständige Gemeinde, ist heue aber ein Stadtteil von Zittau. Es wurde erstmals 1391 urkundlich erwähnt, vergrößerte sich aber erst im Rahmen der Industrialisierung im 1900 Jahrhundert wesentlich.';
					break;
				case 16:
					$item['coordinates'] = '50.8694787,14.6741912';
					$item['name'] = 'Saalendorf';
					$item['popuptext'] = 'Saalendorf ist ein Ortsteil von Waltersdorf, der im Jahr 1557 östlich von Waltersdorf entstand. Der Ort wird durchquert vom Umgebindehaus-Radweg, der hier entlang der Straße zwischen Bertsdorf und Waltersdorf führt.';
					break;
				case 17:
					$item['coordinates'] = '50.9346822,14.6111156';
					$item['name'] = 'Seifhennersdorf';
					$item['popuptext'] = 'Wie viele andere Ortschaften in der Region entstand Seifhennersdorf im Zuge der Besiedlung im 13. Jahrhundert als Waldhufendorf. Auch hier entstanden später die typischen Umgebindehäuser, die mit dem Weberhandwerk verbunden sind. Zu den Sehenswürdigkeiten zählen u. a. das restaurierte Restaurant auf dem Burgsberg, das Karasek-Museum (Heimatmuseum) und das Puppenmuseum.';
					break;
				case 18:
					$item['coordinates'] = '50.9372348,14.6803153';
					$item['name'] = 'Spitzkunnersdorf';
					$item['popuptext'] = 'In Spitzkunnersdorf, das ein Ortsteil von Leutersdorf ist und Mitte des 14. Jahrhunderts erstmals erwähnt wurde, ist die Nikolaikirche zu sehen. Diese Kirche wurde bereits 1372 erstmals erwähnt. In ihrer heutigen Form wurde sie von 1712 - 1716 erbaut. Die Ortschaft hat eine Einwohnerzahl von 1820 (Stand 1997).';
					break;
				case 19:
					$item['coordinates'] = '50.869494,14.650735';
					$item['name'] = 'Waltersdorf';
					$item['popuptext'] = 'Waltersdorf liegt zu Füßen der Lausche, des höchsten Berges des Zittauer Gebirges, und ist damit Ausgangspunkt für viele Wanderungen. Der Name kann zurückgeführt werden auf seine Lage "am Wald" oder auf einen Siedler mit den Namen "Walter", der die Ortschaft im 13. Jahrhundert als Waldhufendorf gründete. Zu den Sehenswürdigkeiten gehören u. a. die zahlreichen Umgebindehäuser, das Volkskundemuseum und das Lauschehochmoor. Es ist außerdem ein beliebter Wintersportort.';
					break;
				case 20:
					$item['coordinates'] = '50.8987768,14.8093505';
					$item['name'] = 'Zittau';
					$item['popuptext'] = 'Der Stadt Zittau hat das Gebirge seinen Namen zu verdanken. Bereits um das Jahr 1000 bestanden hier erste Siedlungen am Ufer der Mandau. Urkundlich erwähnt wird Zittau das erste Mal im Jahr 1238. Durch die Grenzziehung nach dem 2. Weltkrieg wurde Zittau zur Stadt im Dreiländereck, in der die Beziehungen zu den Nachbarländern eine besondere Rolle spielen.';
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

		for ($i = 1; $i <= 6; $i++) {
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
				case 3:
					$item['coordinates'] = '50.844664,14.675921';
					$item['name'] = 'Falkenstein';
					$item['alias'] = 'falkenstein';
					$item['popuptext'] = 'Der Falkenstein liegt direkt an der Grenze zwischen Deutschland und der Tschechischen Republik südwestlich der Jonsdorfer Felsenstadt. Er ist wie einige andere Felsen in der Region ein beliebtes Ziel für Kletterer.';
					break;
				case 4:
					$item['coordinates'] = '50.848958,14.76137';
					$item['name'] = 'Felsgebilde Schildkröte';
					$item['alias'] = 'felsgebilde_schildkroete';
					$item['popuptext'] = 'Das Felsgebilde Schildkröte ist eines der markanten Gebilde auf dem Töpfer. Auf dem ersten Blick kann man erkennen, warum der Fels diesen Namen trägt. Sie wurde zum Naturdenkmal erklärt. Rund um die Schildkröte wächst Calluna-Heide auf Sandstein.';
					break;
			
				case 5:
					$item['coordinates'] = '50.844664,14.675921';
					$item['name'] = 'Falkenstein';
					$item['alias'] = 'falkenstein';
					$item['popuptext'] = 'Der Falkenstein liegt direkt an der Grenze zwischen Deutschland und der Tschechischen Republik südwestlich der Jonsdorfer Felsenstadt. Er ist wie einige andere Felsen in der Region ein beliebtes Ziel für Kletterer.';
					break;
				case 6:
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

		for ($i = 1; $i <= 19; $i++) {
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
					$item['popuptext'] = 'Der Breiteberg ist aus Basalt und Phonolith aufgebaut, was ein gutes Ausgangsgestein für nährstoffreiche Böden ist. Unter anderem wegen dieser Tatsache finden sich am Breiteberg verschiedenste Biotope und seltene Pflanzen: Nadelwald auf der Nordseite, auf der Südseite ein Laubmischwald mit Elementen des Labkraut-Eichen-Hainbuchenwalds und ein Silikattrockenrasen.<a href="http://naturparkblicke.de/images/Bilder/ffh_spa_breiteberg.jpg" target="_blank" title="FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz  "Breiteberg">FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz  "Breiteberg"</a>';
					break;
				case 2:
					$item['coordinates'] = '50.885682,14.714463';
					$item['name'] = 'FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"';
					$item['alias'] = 'seidelsberg';
					$item['popuptext'] = 'Wie der Breitberg besteht der Seidelsberg aus einer Decke aus Phonolith. Hier wächst sowohl Nadelwald als auch ein Labkraut-Eichen-Hainbuchenwald mit abwechslungsreicher Krautschicht. <a href="http://naturparkblicke.de/images/Bilder/ffh_spa_seidelsberg.jpg" target="_blank" title="FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg">FFH-Gebiet Basalt- und Phonolithkuppen der östlichen Oberlausitz "Seidelsberg"</a>';
					break;



				case 3:
					$item['coordinates'] = '50.870288,14.804674';
					$item['name'] = 'FFH-Gebiet Eichgrabener Feuchtgebiet';
					$item['popuptext'] = 'Das FFH-Gebiet <b>Eichgrabener Feuchtgebiet</b> ist eine abwechslungsreiche Landschaft aus Seen mit Verlandungsbereichen, feuchten Mähwiesen und Erlen-Eschen-Bruchwald. In den Verlandungsbereichen wächst Schilf und Rohrkolben. Neben dem Fischotter jagen hier einige Fledermausarten.<br></br><a href="http://naturparkblicke.de/images/stories/ffh_spa_eichgrabener_teichgebiet.jpg" target="_blank" title="FFH-Gebiet Eichgrabener Feuchtgebiet">FFH-Gebiet Eichgrabener Feuchtgebiet</a>';
					break;

				case 4:
					$item['coordinates'] = '50.85213146,14.75484269';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 1 &quot;Ameisenberg und Höhenzug westlich Oybin&quot;';
					$item['popuptext'] = 'Der Ameisenberg und der Höhenzug westlich von Oybin gehören zum FFH-Gebiet <b>Hochlagen des Zittauer Gebirges</b>. Wichtigste Gesteinsart in diesem Gebiet ist Sandstein. Verschiedene FFH-Lebensraumtypen können hier gefunden werden, etwa Silikatfelsen mit Felsspaltenvegetation, Trockene Heiden, Hainsimsen-Buchenwald und Waldmeister-Buchenwald.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_ameisenberg.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 1 "Ameisenberg und Höhenzug westlich Oybin">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 1 "Ameisenberg und Höhenzug westlich Oybin"</a>';
					break;

				case 5:
					$item['coordinates'] = '50.856866,14.656312';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 10 &quot;Grünland östlich Butterberg&quot;';
					$item['popuptext'] = 'Das Grünland östlich vom Butterberg ist geprägt durch artenarme Mähwiesen, die zudem auf meist sauren Böden wachsen. Es ist ein Teilgebiet des FFH-Gebiets Hochlagen des Zittauer Gebirges.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_gruenland_oestlich_butterberg.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 10 "Grünland östlich Butterberg">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 10 "Grünland östlich Butterberg"</a>';
					break;

				case 6:
					$item['coordinates'] = '50.85119552000001,14.71547094';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 11 &quot;Jonsberg&quot;';
					$item['popuptext'] = 'Der 653 m hohe Jonsberg ist der Hausberg von Jonsdorf und gehört mit einer ausgeschriebenen Fläche von rund 109 ha zum FFH-Gebiet Hochlagen des Zittauer Gebirges. Bestimmende Baumarten der Vegetation sind Buche und Fichte, stellenweise stockt ein Hainsimsen-Buchenwald. Um den Berg herum führt ein 4,5 km langer Rundweg, der im Winter als Skiwanderweg genutzt werden kann. <br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_jonsberg.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 11 "Jonsberg">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 11 "Jonsberg"</a>';
					break;

				case 7:
					$item['coordinates'] = '50.84969,14.64613';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 12 &quot;Lausche&quot;';
					$item['popuptext'] = 'Die Lausche gehört zum FFH-Gebiet Hochlagen des Zittauer Gebirges. Auf dem nährstoffreichen Untergrund stockt ein Waldmeister-Buchenwald mit einem großen Arteninventar, wie etwa Einbeere, Lerchensporn und Schildfarn. Nordöstlich finden sich Bergmähwiesen, auf denen die seltene Arnika wächst. Bemerkenswert ist die hier vorkommende Alpenspitzmaus, ein Kaltzeitrelikt der Eiszeit.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_lausche.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 12 "Lausche">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 12 "Lausche"</a>';
					break;

				case 8:
					$item['coordinates'] = '50.82977,14.75253';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 13 &quot;Grünland westlich Lückendorf&quot;';
					$item['popuptext'] = 'Die Lückendorfer Wiesen sind ein Gebiet von zusammenhängenden Wiesenflächen, die wegen ihres Charakters Heimat einiger besonderer Tier- und Pflanzenarten sind. Sie sind teil des Europäischen Vogelschutzgebietes Zittauer Gebirge, da sie Lebensraum für seltene Wiesenbrüter (z. B. Braunkehlchen) bieten. Die Grünfläche westlich von Lückendorf ist leicht nach Süden geneigt.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_gruenland_westl_lueckendorf.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 13 "Grünland westlich Lückendorf">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 13 "Grünland westlich Lückendorf"</a>';
					break;

				case 9:
					$item['coordinates'] = '50.848277,14.762446';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 2 &quot;Töpfer und Felsengasse&quot;';
					$item['popuptext'] = 'Der über dem Zittauer Becken aufragende 582 m hohe Töpfer und die nah gelegene Felsengasse sind Teil des FFH-Gebiets Hochlagen des Zittauer Gebirges. Wegen des dominierenden Sandsteins finden sich hier Biotope wie Nadelwald, Felsen mit Spaltenvegetation sowie eine Silikatschutthalde. Eine kleine Fläche wird von einem Waldmeister-Hainsimsen-Wald eingenommen.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_grosse_felsengasse.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 2 "Töpfer und Felsengasse">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 2 "Töpfer und Felsengasse"</a>';
					break;

				case 10:
					$item['coordinates'] = '50.82656,14.73031';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 3 &quot;Hochwald&quot;';
					$item['popuptext'] = 'Auf dem 749 m hohen Hochwald, der aus einer Phonolithkuppe aufgebaut ist, wachsen großflächig naturnahe Hainsimsen-Buchenwälder. Auch einzelne Schutthalden aus Silikatgestein kommen hier vor. Das Gebiet des Hochwalds gehört deshalb zum FFH-Gebiet Hochlagen des Zittauer Gebirges.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_hochwald.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 3 "Hochwald">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 3 "Hochwald"</a>';
					break;

				case 11:
					$item['coordinates'] = '50.84522908,14.70698118';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 4 &quot;Grünland südlich Jonsdorf&quot;';
					$item['popuptext'] = 'Südlich von Jonsdorf liegt ein kleines Stück Grünland, das Teil des FFH-Gebiets Hochlagen des Zittauer Gebirges ist. Hier findet sich eine magere Flachland-Mähwiese mit Arten wie Wiesen-Glockenblume, Wiesen-Rispengras und Margerite.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_hochlagen_gruenland_suedl_jonsdorf.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 4 "Grünland südlich Jonsdorf">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 4 "Grünland südlich Jonsdorf"</a>';
					break;

				case 12:
					$item['coordinates'] = '50.83321,14.770492';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 5 &quot;Grünland nordöstlich Lückendorf&quot;';
					$item['popuptext'] = 'Die Lückendorfer Wiesen sind ein Gebiet von zusammenhängenden Wiesenflächen, die wegen ihres Charakters Heimat einiger besonderer Tier- und Pflanzenarten sind. Sie sind teil des Europäischen Vogelschutzgebietes Zittauer Gebirge, da sie Lebensraum für seltene Wiesenbrüter bieten. Die Gründfläche nordöstlich von Lückendorf ist gebirgsuntypisch kaum geneigt.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_hochlagen_gruenland_noe_Lueckendorf.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 5 "Grünland nordöstlich Lückendorf">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 5 "Grünland nordöstlich Lückendorf"</a>';
					break;

				case 13:
					$item['coordinates'] = '50.83545900000001,14.718797';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 6 &quot;Johannisstein&quot;';
					$item['popuptext'] = 'Am Johannisstein befindet sich ein kleiner Bereich, der zum FFH-Gebiet Hochlages des Zittauer Gebirges gehört. Es handelt sich um eine magere Flachland-Mähwiese am Hang des Johannissteins, auf der vorwiegend niedrigwüchsige Pflanzenarten zu finden sind.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_johannisstein.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 6 "Johannisstein">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 6 "Johannisstein"</a>';
					break;

				case 14:
					$item['coordinates'] = '50.845581,14.688567';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 7 &quot;Jonsdorfer Felsenstadt und
Mühlsteinbrüche&quot;';
					$item['popuptext'] = 'Das Naturschutzgebiet Jonsdorfer Felsenstadt ist ein Komplex aus Sandsteinbergen, die v. a. wegen ihrer besonderen Eignung für seltene Felsbrüter wie Uhu und Wanderfalke unter Schutz gestellt worden sind.  Während der Brutzeit dürfen die hier angelegten Horstschutzzonen nicht betreten werden, damit die seltenen Tiere vor menschlichen Einflüssen geschützt werden können.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_jonsdorfer_felsenstadt.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 7 "Jonsdorfer Felsenstadt und
Mühlsteinbrüche">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 7 "Jonsdorfer Felsenstadt und
Mühlsteinbrüche"</a>';
					break;

				case 15:
					$item['coordinates'] = '50.84173337000001,14.7084403';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 8 &quot;Grünland an der Finsteren Tülke&quot;';
					$item['popuptext'] = 'Westlich der Finsteren Tülke (Sandsteingasse) befindet sich eine magere Mähwiese, die zum FFH-Gebiet Hochlages des Zittauer Gebirges gehört. Je nach Feuchte findet man hier Schlangenknöterich und Faltterbinse oder auch Wiesen-Glockenblume und Kleine Bibernelle.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_gl_westl_butterberg.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 8 "Grünland an der Finsteren Tülke">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 8 "Grünland an der Finsteren Tülke"</a>';
					break;

				case 16:
					$item['coordinates'] = '50.858234,14.653505';
					$item['name'] = 'FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 9 &quot;Grünland westlich Butterberg&quot;';
					$item['popuptext'] = 'Am Butterberg befindet sich eine Teilfläche des FFH-Gebiets Hochlagen des Zittauer Gebirges, die geprägt ist von Flachland-Mähwiesen. Aber auch der Lebensraumtyp der trockenen Heiden kann hier angetroffen werden. Hier kann beobachtet werden, wie ein ehemaliger Borstgrasrasen langsam verbuscht.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_gl_finstere_tuelke.jpg" target="_blank" title="FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 9 "Grünland westlich Butterberg">FFH-Gebiet Hochlagen des Zittauer Gebirges - Teilgebiet 9 "Grünland westlich Butterberg"</a>';
					break;

				case 17:
					$item['coordinates'] = '50.923401,14.730111';
					$item['name'] = 'FFH-Gebiet Mandautal';
					$item['popuptext'] = 'Das Mandautal wurde wegen seines naturnahen Lebensraumes zum FFH-Gebiet erklärt. Zwischen Hainewalde und Mittelherwigsdorf liegt das idyllische Roschertal, das geologisch gesehen ein Durchbruchstal ist und verschiedene Biotope bietet, etwa Labkraut-Eichen-Hainbuchen-Wälder und Schlucht- und Hangmischwälder. Durch das Schülertal zwischen Mittelherwigsdorf und Hörnitz führt ein Barfußweg.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_spa_mandautal.jpg" target="_blank" title="FFH-Gebiet Mandautal">FFH-Gebiet Mandautal</a>';
					break;

				case 18:
					$item['coordinates'] = '50.920756,14.712406';
					$item['name'] = 'FFH-Gebiet Separate Fledermausquartiere und -habitate in der Lausitz';
					$item['popuptext'] = 'Innerhalb des Naturparks liegen zwei Teilgebiete der separaten Fledermausquartiere und -habitate der Oberlausitz. Ziel ist der Schutz der Lebensräume von Fledermausarten, die alle nach dem Bundesnaturschutzgesetz streng geschützt sind.<br></br><a href="http://naturparkblicke.de/images/Bilder/ffh_lrt_fledermausquartiere.jpg" target="_blank" title="FFH-Gebiet Separate Fledermausquartiere und -habitate in der Lausitz">FFH-Gebiet Separate Fledermausquartiere und -habitate in der Lausitz</a>';
					break;

				case 19:
					$item['coordinates'] = '50.844389,14.689588';
					$item['name'] = 'SPA &quot;Zittauer Gebirge&quot;, Jonsdorfer Felsenstadt mit Dackel, Nonnenfelsen und Buchberg';
					$item['popuptext'] = 'Im Süden des Naturparks liegt das Vogelschutzgebiet Zittauer Gebirge, das zum Schutz gefährdeter Brutvogelarten wie Uhu, Wanderfalke, Eisvogel, Neuntöter und Schwarzstorch eingerichtet wurde. Vor allem Felsbrüter wie Wanderfalke und Uhu finden im Zittauer Gebirge viele geeignete Lebensräume.';
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

		for ($i = 1; $i <= 17; $i++) {
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

				case 3:
					$item['coordinates'] = '50.88170158999999,14.67837811';
					$item['name'] = 'Goldfabianteich';
					$item['popuptext'] = 'Der Goldfabiansteich ist ein naturnaher Teich, der von einer Mischung aus Sumpfgebüsch, Röhricht und seggen- und binsenreicher Feuchtwiese umgeben ist und sich malerisch in das Bergpanorama einfügt. Er wurde deshalb zum Flächennaturdenkmal erklärt.';
					break;

				case 4:
					$item['coordinates'] = '50.87371339,14.71058607';
					$item['name'] = 'Bertsdorfer Wasser';
					$item['popuptext'] = 'Das Bertsdorfer Wasser, welches auch als &quot;Bertse&quot; bezeichnet wird, entspringt auf der Nordostseite am Pocheberg unter Basalt. Es fließt auf einer Länge von 5,1 km durch Bertsdorf und mündet schließlich in Bertsdorfer Ortsteil Hörnitz in die Mandau.';
					break;

				case 5:
					$item['coordinates'] = '50.85189459,14.7516346';
					$item['name'] = 'Goldbach';
					$item['popuptext'] = 'Der Goldbach ist ein Nebenfluss der Mandau und entspringt im Zittauer Gebirge nördlich von Oybin. Er durchfließt erst Oybin und führt dann entlang der Teufesmühle hinunter nach Olbersdorf, wo er größtenteils verbaut worden ist. In Zittau mündet der 9,2 km lange Bach in die Mandau.';
					break;

				case 6:
					$item['coordinates'] = '50.87766704,14.74644184';
					$item['name'] = 'Grundbach';
					$item['popuptext'] = 'Der Grundbach entspringt im südlichsten Teil des Ortes Jonsdorf kurz vor dem Übergang zur Tschechischen Republik. Er durchfließt den Kurort Jonsdorf, fließt um die ehemaligen Abraumhalden des Olbersdorfer Sees und mündet schließlich als einziger Zufluss in diesen. Im Zuge des Braunkohletagebaus musste der Bach umgeleitet werden, da sich der Tagebau andernfalls kontinuierlich mit Wasser gefüllt hätte.';
					break;

				case 7:
					$item['coordinates'] = '50.85748908,14.73878145';
					$item['name'] = 'Hungerbrunnen';
					$item['popuptext'] = 'Der Hungerbrunnen ist die Quelle des Hungerbornwassers, das nach Olbersdorf fließt und zur Trinkwasserversorgung dient. Sein Name geht auf eine Sage zurück, nach der ein alter ausgezehrte Mann sein weniges Brot uneigennützig mit einer Frau und deren Kindern teilt um deren Hunger zu stillen.';
					break;

				case 8:
					$item['coordinates'] = '50.86718643,14.79627728';
					$item['name'] = 'Pfaffenbach';
					$item['popuptext'] = 'Der Eichgrabener Pfaffenbach entspringt am unteren Nordhang des Töpfers. Der Bach durchfließt das Eichgrabener Feuchtgebiet und mündet in Zittau in die Neiße.';
					break;

				case 9:
					$item['coordinates'] = '50.92556484,14.74336267';
					$item['name'] = 'Landwasser';
					$item['popuptext'] = 'Das 15,2 km lange Landwasser entspringt westlich von Walddorf und speist sich aus den Boden- und Quellwässern der Süd- und Südosthänge des Kottmar. Starkregenereignisse führen schnell zu Hochwasser, da der Abtransport des Wassers allein durch Landwasser und Pließnitz erfolgt. Der Bachlauf führt größtenteils durch bebaute Bereiche. Ein Stück Bachaue an der Mündung in die Mandau gehört zum FFH-Gebiet Mandautal.';
					break;

				case 10:
					$item['coordinates'] = '50.886412,14.645756';
					$item['name'] = 'Lausur';
					$item['popuptext'] = 'Das Quellgebiet der 13,6 km langen Lausur liegt südlich der tschechischen Stadt Krásná Lípa. Auf ihrem Weg nach Osten durchquert sie den Stausee Rybnitský velký rybník.  Auch fließen ihr auf dem Weg zahlreiche kleine Bäche und Rinnsale zu. Bei Herrenwalde überschreitet sie die deutsche Grenze und fließt noch ein Stück durch den Naturpark um in Großschönau in die Mandau zu münden.';
					break;

				case 11:
					$item['coordinates'] = '50.9082694,14.74640965';
					$item['name'] = 'Mandau';
					$item['popuptext'] = 'Die Mandau entspringt nördlich der tschechischen Stadt Vlcí hora. Mit einer Länge von 40,9 km durchfließt sie den Naturpark in östliche Richtung. Ihre Wasserführung ist sehr unregelmäßig. In der zweiten Hälfte des 19. Jh. wurde der Fluss stellenweise begradigt.  Heute sind einige Abschnitte der Mandau  als Landschaftsschutz- und auch FFH-Gebiet Mandautal geschützt. Östlich der Stadt Zittau mündet der Fluss letztendlich in der Neiße.';
					break;

				case 12:
					$item['coordinates'] = '50.908006,14.701036';
					$item['name'] = 'Mandauknie Hainewalde';
					$item['popuptext'] = 'Das Mandauknie in Hainewalde verdankt seinem Namen der Mandau, die an dieser Stelle beinahe im rechten Winkel abbiegt und so die Form eines Knies entsteht. Von hier aus eröffnet sich ein sehr guter Blick auf die Hainewalder Kirche.';
					break;

				case 13:
					$item['coordinates'] = '50.87153333,14.82195139';
					$item['name'] = 'Neiße';
					$item['popuptext'] = 'Die 254 km lange Neiße hat ihre Quelle im Ort Nová Ves nad Nisou im Isergebirge. Die deutsche Seite erreicht sie in Hartau. Den Naturpark berührt die Lausitzer Neiße nur auf einem kurzen Stück von 1km bis zum Dreiländereck, um danach den Grenzfluss zu Polen zu bilden.';
					break;

				case 14:
					$item['coordinates'] = '50.89115026,14.7779417';
					$item['name'] = 'Olbersdorfer See';
					$item['popuptext'] = 'Der 60 ha große See geht auf einen ehemaligen Braunkohletagebau zurück, der 1991 eingestellt wurde. Die Flutung des Sees wurde 1999 beendet. Im selben Jahr fand auch die Landesgartenschau auf dem Gelände um den See statt. Heute gilt er als Naherholungsort für die Bevölkerung. Jedoch finden auch zahlreiche Pflanzen- und einige Tierarten (z.B. Fledermäuse) einen Lebensraum auf den ehemaligen Halden.';
					break;

				case 15:
					$item['coordinates'] = '50.87086981,14.68642473';
					$item['name'] = 'Pochebach';
					$item['popuptext'] = 'Der Name der Poche leitet sich vermutlich aus dem Begriff des Pochwerks ab, mit dem in früheren Zeiten Erz zerkleinert wurde. Die Poche entsteht aus dem Zusammenfließen kleiner Rinnsale des Mönchsloches bei Neu-Jonsdorf. In ihrer Aue findet sich die typische Ufer- und Grünlandvegetation. Auf ihrem Weg zur Mündung in die Mandau innerhalb Großschönaus speist sie sowohl den Gondel- als auch den Pocheteich.';
					break;

				case 16:
					$item['coordinates'] = '50.93535452,14.67135876';
					$item['name'] = 'Spitzkunnersdorfer Wasser';
					$item['popuptext'] = 'Aus mehreren Rinnsalen entsteht, umgeben vom Großen und Schwarzen Stein das Spitzkunnersdorfer Wasser. Nach nur 6 km mündet das kleine Gewässer in Niederorderwitz im Landwasser.';
					break;
				
				case 17:
					$item['coordinates'] = '50.8394163,14.80454922';
					$item['name'] = 'Weißbach';
					$item['popuptext'] = 'Der Weißbach entspringt im unteren Weißbachtal und bildet fortan die Grenze zu Tschechien. Aufgrund des Bergbaus in Hartau musste der Bach umgeleitet werden, so dass man seinen ursprünglichen Verlauf kaum noch nachvollziehen kann. Im 19. Jh. wurde zusätzlich begonnen, das Quellgebiet des Weißbachs zur Trinkwasserversorgung der Zittauer zu nutzen. Nach etwas mehr als 1,7 km endet der Bach im Gebiet alter Bergbaustollen zwischen Hartau  und Loucná.';
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

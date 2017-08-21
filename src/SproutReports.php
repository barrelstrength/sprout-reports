<?php
/**
 * Sprout Reports plugin for Craft CMS 3.x
 *
 * Powerful custom reports.
 *
 * @link      barrelstrengthdesign.com
 * @copyright Copyright (c) 2017 Barrelstrength
 */

namespace barrelstrength\sproutreports;

use barrelstrength\sproutcore\traits\PlugAble;
use barrelstrength\sproutreports\models\Settings;
use barrelstrength\sproutcore\services\sproutreports\DataSources;
use barrelstrength\sproutcore\SproutCoreHelper;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomQuery;
use barrelstrength\sproutreports\services\App;
use Craft;
use craft\base\Plugin;
use barrelstrength\sproutreports\variables\SproutReportsVariable;
use craft\events\DefineComponentsEvent;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use craft\events\RegisterComponentTypesEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\services\UserPermissions;
use craft\events\RegisterUserPermissionsEvent;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\Categories;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\Users;

use craft\web\View;
use craft\events\RegisterTemplateRootsEvent;
/**
 * https://craftcms.com/docs/plugins/introduction
 *asd
 * @author    Barrelstrength
 * @package   SproutReports
 * @since     3
 */
class SproutReports extends Plugin
{
	use PlugAble;
	/**
	 * Enable use of SproutReports::$plugin-> in place of Craft::$app->
	 *
	 * @var \barrelstrength\sproutreports\services\App
	 */
	public static $app;

	public $hasSettings = true;
	public static $pluginId = 'sprout-reports';

  public function init()
  {
		parent::init();

	  SproutCoreHelper::registerModule();

	  $this->setComponents([
		  'app' => App::class
	  ]);

		self::$app = $this->get('app');

	  // Register our base template path
	  Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function(RegisterTemplateRootsEvent $e) {
		  $e->roots['sprout-reports'] = $this->getBasePath().DIRECTORY_SEPARATOR.'templates';
	  });

	  Event::on(CraftVariable::class, CraftVariable::EVENT_DEFINE_COMPONENTS, function(DefineComponentsEvent $e) {
		  $e->components['sproutreports'] = SproutReportsVariable::class;
	  });

		Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function (RegisterUserPermissionsEvent $event) {

			$name = static::t('Sprout Reports');

			$event->permissions[$name]['sproutReports-editReports']     = ['label' => $this::t('Edit Reports')];
			$event->permissions[$name]['sproutReports-editDataSources'] = ['label' => $this::t('Edit Data Sources')];
			$event->permissions[$name]['sproutReports-editSettings']    = ['label' => $this::t('Edit Plugin Settings')];
		});

		Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {

			$event->rules['sprout-reports/reports'] = 'sprout-reports/reports/index';
			$event->rules['sprout-reports/reports/<groupId:\d+>'] = 'sprout-reports/reports/index';

			$event->rules['sprout-reports/reports/<dataSourceId>/new'] = 'sprout-core/reports/edit-report';
			$event->rules['sprout-reports/reports/<dataSourceId>/edit/<reportId:\d+>'] = 'sprout-core/reports/edit-report';

			$event->rules['sprout-reports/datasources'] = ['template' => 'sprout-reports/datasources/index'];

			$event->rules['sprout-reports/reports/view/<reportId:\d+>'] = 'sprout-core/reports/results-index';

			$event->rules['sprout-reports/settings']         = 'sprout-core/settings/edit-settings';
			$event->rules['sprout-reports/settings/general'] = 'sprout-core/settings/edit-settings';
		});

		Event::on(DataSources::class, DataSources::EVENT_REGISTER_DATA_SOURCES, function(RegisterComponentTypesEvent
		                                                                                  $event) {
		  $event->types[] = new Categories();
		  $event->types[] = new CustomQuery();

			$isCraftPro = Craft::$app->getEdition() == Craft::Pro ? true : false;

			if ($isCraftPro == true)
			{
				$event->types[] = new Users();
			}
		});
  }

	/**
	 * Installs default group "Sprout Reports" after installation
	 */
  public function afterInstall()
  {
	  $defaultGroup = SproutReports::$app->reportGroups->createGroupByName('Sprout Reports');

	  if (Craft::$app->getPlugins()->getPlugin('sprout-reports'))
	  {
		 SproutReports::$app->reports->registerReports(new Users(), $defaultGroup);
	  }
  }

	/**
	 * @return Settings
	 */
	protected function createSettingsModel()
	{
		return new Settings();
	}

	public function getCpNavItem()
	{
		$parent = parent::getCpNavItem();

		$parent['url'] = 'sprout-reports';

		return array_merge($parent, [
			'subnav' => [
				'reports' => [
					'label' => static::t('Reports'),
					'url' => 'sprout-reports/reports'
				],
				'datasources' => [
					'label' => static::t('Data Sources'),
					'url' => 'sprout-reports/datasources'
				],
				'settings' => [
					'label' => static::t('Settings'),
					'url' => 'sprout-reports/settings/general'
				]
			]
		]);
	}
}

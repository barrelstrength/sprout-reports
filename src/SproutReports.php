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

use barrelstrength\sproutreports\models\Settings;
use barrelstrength\sproutcore\services\sproutreports\DataSources;
use barrelstrength\sproutcore\SproutCoreHelper;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomQuery;
use Craft;
use craft\base\Plugin;
use barrelstrength\sproutreports\variables\SproutReportsVariable;
use yii\base\Event;
use craft\events\RegisterComponentTypesEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\services\UserPermissions;
use craft\events\RegisterUserPermissionsEvent;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\Categories;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\Users;

/**
 * https://craftcms.com/docs/plugins/introduction
 *asd
 * @author    Barrelstrength
 * @package   SproutReports
 * @since     3
 */
class SproutReports extends Plugin
{
	/**
	 * Enable use of SproutReports::$plugin-> in place of Craft::$app->
	 *
	 * @var \barrelstrength\sproutreports\services\App
	 */
	public static $app;

	public $hasSettings = true;

  public function init()
  {
		parent::init();

	  SproutCoreHelper::registerModule();

		self::$app = $this->get('app');

		Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function (RegisterUserPermissionsEvent $event) {

			$event->permissions['sproutReports']['sproutReports-editReports']     = ['label' => $this::t('Edit Reports')];
			$event->permissions['sproutReports']['sproutReports-editDataSources'] = ['label' => $this::t('Edit Data Sources')];
			$event->permissions['sproutReports']['sproutReports-editSettings']    = ['label' => $this::t('Edit Plugin Settings')];

		});

		Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {

			$event->rules['sproutreports/reports'] = 'sprout-reports/reports/index';
			$event->rules['sproutreports/reports/<groupId:\d+>'] = 'sprout-reports/reports/index';

			$event->rules['sproutreports/reports/<dataSourceId>/new'] = 'sprout-core/reports/edit-report';
			$event->rules['sproutreports/reports/<dataSourceId>/edit/<reportId:\d+>'] = 'sprout-core/reports/edit-report';

			$event->rules['sproutreports/datasources'] = ['template' => 'sproutreports/datasources/index'];

			$event->rules['sproutreports/reports/view/<reportId:\d+>'] = 'sprout-core/reports/results-index';

			$event->rules['sproutreports/settings']         = 'sprout-core/settings/edit-settings';
			$event->rules['sproutreports/settings/general'] = 'sprout-core/settings/edit-settings';
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

	  if (Craft::$app->getPlugins()->getPlugin('sproutreports'))
	  {
		 SproutReports::$app->reports->registerReports(new Users(), $defaultGroup);
	  }
  }

	/**
	 * @param string $message
	 * @param array  $params
	 *
	 * @return string
	 */
	public static function t($message, array $params = [])
	{
		return Craft::t('sproutreports', $message, $params);
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

		$parent['url'] = 'sproutreports';

		return array_merge($parent, [
			'subnav' => [
				'reports' => [
					'label' => static::t('Reports'),
					'url' => 'sproutreports/reports'
				],
				'datasources' => [
					'label' => static::t('Data Sources'),
					'url' => 'sproutreports/datasources'
				],
				'settings' => [
					'label' => static::t('Settings'),
					'url' => 'sproutreports/settings/general'
				]
			]
		]);
	}

	/**
	 * @return mixed
	 */
	public function defineTemplateComponent()
	{
		return SproutReportsVariable::class;
	}
}

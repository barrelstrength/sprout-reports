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

use craft\base\Plugin;
use barrelstrength\sproutreports\models\Settings;
use barrelstrength\sproutreports\services\DataSources;
use barrelstrength\sproutreports\variables\SproutReportsVariable;
use yii\base\Event;
use craft\events\RegisterComponentTypesEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\Categories;
/**
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Barrelstrength
 * @package   SproutReports
 * @since     3
 */
class SproutReports extends Plugin
{
	/**
	 * Enable use of SproutEncodeEmail::$plugin-> in place of Craft::$app->
	 *
	 * @var \barrelstrength\sproutreports\services\Api
	 */
	public static $api;

	public $hasSettings = true;

  public function init()
  {
		parent::init();

		self::$api = $this->get('api');

		Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {

			$event->rules['sproutreports'] = 'sprout-reports/reports/index';
			$event->rules['sproutreports/reports/<pluginId>/<dataSourceKey:{handle}>/new'] = 'sprout-reports/reports/edit-report';

		});

		Event::on(DataSources::class, DataSources::EVENT_REGISTER_DATA_SOURCES, function(RegisterComponentTypesEvent $event) {
		  $event->types[] = new Categories;
		});
  }

	/**
	 * @param string $message
	 * @param array  $params
	 *
	 * @return string
	 */
	public static function t($message, array $params = [])
	{
		return \Craft::t('sproutReports', $message, $params);
	}

	protected function createSettingsModel()
	{
		return new Settings();
	}

	protected function settingsHtml()
	{
		return \Craft::$app->getView()->renderTemplate('sprout-reports/_cp/settings', [
			'settings' => $this->getSettings()
		]);
	}

	public function defineTemplateComponent()
	{
		return SproutReportsVariable::class;
	}
}

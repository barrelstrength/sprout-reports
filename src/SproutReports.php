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
}

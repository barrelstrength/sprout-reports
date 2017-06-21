<?php
namespace barrelstrength\sproutreports\services;

use craft\base\Component;
use craft\helpers\Json;
use Craft;

class Settings extends Component
{
	/**
	 * Save the plugin settings to the database
	 *
	 * @param $settings
	 *
	 * @return bool
	 */
	public function saveSettings($settings)
	{
		$plugin      = Craft::$app->plugins->getPlugin('sproutReports');

		$reportSettings = $plugin->getSettings();

		if (isset($settings["pluginNameOverride"]))
		{
			$reportSettings->pluginNameOverride = $settings["pluginNameOverride"] != null ?
				$settings["pluginNameOverride"] :
				$reportSettings->pluginNameOverride;
		}

		$settings = Json::encode($reportSettings);

		$affectedRows = Craft::$app->db->createCommand()->update('{{%plugins}}', [
			'settings' => $settings
		], [
			'handle' => strtolower($plugin->handle)
		])->execute();

		return (bool) $affectedRows;
	}

}

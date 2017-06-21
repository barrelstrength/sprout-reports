<?php

namespace barrelstrength\sproutreports\models;

use barrelstrength\sproutreports\SproutReports;
use craft\base\Model;

class Settings extends Model
{
	public $pluginNameOverride = '';

	public function getSettingsNavItems()
	{
		return [
			'general' => [
				'label' => SproutReports::t('General'),
				'url' => 'sproutreports/settings',
				'selected' => 'general',
				'template' => 'sproutreports/_settings/index'
			]
		];
	}
}
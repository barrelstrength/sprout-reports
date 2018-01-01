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
                'label' => Craft::t('sprout-reports','General'),
                'url' => 'sprout-reports/settings/general',
                'selected' => 'general',
                'template' => 'sprout-reports/_settings/general'
            ]
        ];
    }
}
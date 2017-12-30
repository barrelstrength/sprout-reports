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
                'url' => 'sprout-reports/settings/general',
                'selected' => 'general',
                'template' => 'sprout-reports/_settings/general'
            ]
        ];
    }
}
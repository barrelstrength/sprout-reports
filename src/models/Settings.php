<?php

namespace barrelstrength\sproutreports\models;

use barrelstrength\sproutbase\base\SproutSettingsInterface;
use barrelstrength\sproutbase\SproutBase;
use craft\base\Model;
use Craft;
use craft\helpers\StringHelper;

/**
 *
 * @property array $settingsNavItems
 */
class Settings extends Model implements SproutSettingsInterface
{
    /**
     * @var string
     */
    public $pluginNameOverride = '';

    /**
     * @inheritdoc
     */
    public function getSettingsNavItems(): array
    {
        return [
            'general' => [
                'label' => Craft::t('sprout-reports', 'General'),
                'url' => 'sprout-reports/settings/general',
                'selected' => 'general',
                'template' => 'sprout-base-reports/settings/general'
            ]
        ];
    }

    /**
     * Shared permissions they may be prefixed by another plugin. Before checking
     * these permissions the plugin name will be determined from the URL and appended.
     *
     * @example
     * /admin/sprout-reports/page => sproutReports-viewReports
     * /admin/sprout-forms/page => sproutForms-viewReports
     *
     * @return array
     */
    public static function getSharedPermissions(): array
    {
        return [
            'viewReports',
            'editReports'
        ];
    }
}
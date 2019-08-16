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

use barrelstrength\sproutbase\base\BaseSproutTrait;
use barrelstrength\sproutbasereports\SproutBaseReports;
use barrelstrength\sproutbasereports\SproutBaseReportsHelper;
use barrelstrength\sproutreports\widgets\Number as NumberWidget;
use barrelstrength\sproutreports\datasources\CustomTwigTemplate;
use barrelstrength\sproutbasereports\models\Settings;
use barrelstrength\sproutbasereports\services\DataSources;
use barrelstrength\sproutbase\SproutBaseHelper;
use barrelstrength\sproutreports\datasources\CustomQuery;
use barrelstrength\sproutreports\services\App;
use Craft;
use craft\base\Plugin;
use barrelstrength\sproutreports\web\twig\variables\SproutReportsVariable;
use craft\helpers\UrlHelper;
use craft\services\Dashboard;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use craft\events\RegisterComponentTypesEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\services\UserPermissions;
use craft\events\RegisterUserPermissionsEvent;
use yii\base\InvalidConfigException;

/**
 * https://craftcms.com/docs/plugins/introduction
 *
 *
 * @author    Barrelstrength
 * @package   SproutReports
 * @since     3
 *
 * @property null|array $cpNavItem
 * @property array      $userPermissions
 * @property array      $cpUrlRules
 * @property mixed      $settingsResponse
 */
class SproutReports extends Plugin
{
    use BaseSproutTrait;

    /**
     * Enable use of SproutReports::$app-> in place of Craft::$app->
     *
     * @var App
     */
    public static $app;

    /**
     * Identify our plugin for BaseSproutTrait
     *
     * @var string
     */
    public static $pluginHandle = 'sprout-reports';

    /**
     * @var bool
     */
    public $hasCpSection = true;

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var string
     */
    public $schemaVersion = '1.2.0';

    /**
     * @var string
     */
    public $minVersionRequired = '0.9.3';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();
        SproutBaseReportsHelper::registerModule();

        $this->setComponents([
            'app' => App::class
        ]);

        self::$app = $this->get('app');

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = NumberWidget::class;
        });

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, $this->getCpUrlRules());
        });

        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions['Sprout Reports'] = $this->getUserPermissions();
        });

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            $event->sender->set('sproutReports', SproutReportsVariable::class);
        });

        Event::on(DataSources::class, DataSources::EVENT_REGISTER_DATA_SOURCES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = CustomQuery::class;
            $event->types[] = CustomTwigTemplate::class;
        });
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Redirect to Sprout Reports settings
     *
     * @inheritdoc
     */
    public function getSettingsResponse()
    {
        $url = UrlHelper::cpUrl('sprout-reports/settings');

        return Craft::$app->getResponse()->redirect($url);
    }

    /**
     * @return array|null
     */
    public function getCpNavItem()
    {
        $parent = parent::getCpNavItem();

        // Allow user to override plugin name in sidebar
        if ($this->getSettings()->pluginNameOverride) {
            $parent['label'] = $this->getSettings()->pluginNameOverride;
        }

        if (Craft::$app->getUser()->checkPermission('sproutReports-viewReports')) {
            $parent['subnav']['reports'] = [
                'label' => Craft::t('sprout-reports', 'Reports'),
                'url' => 'sprout-reports/reports'
            ];
        }

        if (Craft::$app->getUser()->checkPermission('sproutReports-editDataSources')) {
            $parent['subnav']['datasources'] = [
                'label' => Craft::t('sprout-reports', 'Data Sources'),
                'url' => 'sprout-reports/datasources'
            ];
        }

        if (Craft::$app->getUser()->getIsAdmin()) {
            $parent['subnav']['settings'] = [
                'label' => Craft::t('sprout-reports', 'Settings'),
                'url' => 'sprout-reports/settings/general'
            ];
        }

        return $parent;
    }

    private function getCpUrlRules(): array
    {
        return [
            '<pluginHandle:sprout-reports>' => [
                'template' => 'sprout-base-reports/index'
            ],
            '<pluginHandle:sprout-reports>/reports' => [
                'route' => 'sprout-base-reports/reports/reports-index-template'
            ],
            '<pluginHandle:sprout-reports>/reports/<groupId:\d+>' =>
                'sprout-base-reports/reports/reports-index-template',
            '<pluginHandle:sprout-reports>/reports/<dataSourceId:\d+>/new' =>
                'sprout-base-reports/reports/edit-report-template',
            '<pluginHandle:sprout-reports>/reports/<dataSourceId:\d+>/edit/<reportId:\d+>' =>
                'sprout-base-reports/reports/edit-report-template',
            '<pluginHandle:sprout-reports>/reports/view/<reportId:\d+>' =>
                'sprout-base-reports/reports/results-index-template',
            '<pluginHandle:sprout-reports>/datasources' =>
                'sprout-reports/data-sources/data-sources-index-template',

            // Settings
            'sprout-reports/settings' =>
                'sprout/settings/edit-settings',
            'sprout-reports/settings/general' =>
                'sprout/settings/edit-settings'
        ];
    }

    /**
     * @return array
     */
    public function getUserPermissions(): array
    {
        return [
            'sproutReports-viewReports' => [
                'label' => Craft::t('sprout-reports', 'View Reports'),
                'nested' => [
                    'sproutReports-editReports' => [
                        'label' => Craft::t('sprout-reports', 'Edit Reports')
                    ]
                ]
            ],
            'sproutReports-editDataSources' => [
                'label' => Craft::t('sprout-reports', 'Edit Data Sources')
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function afterInstall()
    {
        $dataSourceTypes = [
            CustomQuery::class,
            CustomTwigTemplate::class
        ];

        SproutBaseReports::$app->dataSources->installDataSources($dataSourceTypes);
    }
}

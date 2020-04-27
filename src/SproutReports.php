<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports;

use barrelstrength\sproutbase\base\SproutDependencyInterface;
use barrelstrength\sproutbase\base\SproutDependencyTrait;
use barrelstrength\sproutbase\SproutBaseHelper;
use barrelstrength\sproutbasefields\SproutBaseFieldsHelper;
use barrelstrength\sproutbasereports\datasources\CustomQuery;
use barrelstrength\sproutbasereports\datasources\CustomTwigTemplate;
use barrelstrength\sproutbasereports\datasources\Users;
use barrelstrength\sproutbasereports\models\Settings as SproutBaseReportsSettings;
use barrelstrength\sproutbasereports\SproutBaseReports;
use barrelstrength\sproutbasereports\SproutBaseReportsHelper;
use barrelstrength\sproutreports\services\App;
use barrelstrength\sproutreports\web\twig\variables\SproutReportsVariable;
use barrelstrength\sproutreports\widgets\Number as NumberWidget;
use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\helpers\UrlHelper;
use craft\services\Dashboard;
use craft\services\UserPermissions;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use yii\base\Event;
use yii\base\InvalidConfigException;

/**
 * @property null|array $cpNavItem
 * @property array      $userPermissions
 * @property array      $cpUrlRules
 * @property array      $sproutDependencies
 * @property mixed      $settingsResponse
 */
class SproutReports extends Plugin implements SproutDependencyInterface
{
    use SproutDependencyTrait;

    /**
     * Enable use of SproutReports::$app-> in place of Craft::$app->
     *
     * @var App
     */
    public static $app;

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
    public $schemaVersion = '1.3.3';

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
        SproutBaseFieldsHelper::registerModule();
        SproutBaseReportsHelper::registerModule();

        $this->setComponents([
            'app' => App::class
        ]);

        self::$app = $this->get('app');

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, static function(RegisterComponentTypesEvent $event) {
            $event->types[] = NumberWidget::class;
        });

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, $this->getCpUrlRules());
        });

        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions['Sprout Reports'] = $this->getUserPermissions();
        });

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, static function(Event $event) {
            $event->sender->set('sproutReports', SproutReportsVariable::class);
        });
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
     * @return array
     */
    public function getSproutDependencies(): array
    {
        return [
            SproutDependencyInterface::SPROUT_BASE,
            SproutDependencyInterface::SPROUT_BASE_REPORTS
        ];
    }

    /**
     * @return SproutBaseReportsSettings
     */
    protected function createSettingsModel(): SproutBaseReportsSettings
    {
        return new SproutBaseReportsSettings();
    }

    /**
     * @inheritDoc
     */
    protected function afterInstall()
    {
        $dataSourceTypes = [
            CustomQuery::class,
            CustomTwigTemplate::class,
            Users::class
        ];

        SproutBaseReports::$app->dataSources->installDataSources($dataSourceTypes);
    }

    private function getCpUrlRules(): array
    {
        return [
            // Reports
            '<pluginHandle:sprout-reports>/<pluginSection:reports>/<groupId:\d+>' => [
                'route' => 'sprout-base-reports/reports/reports-index-template'
            ],
            '<pluginHandle:sprout-reports>/<pluginSection:reports>/<dataSourceId:\d+>/new' => [
                'route' => 'sprout-base-reports/reports/edit-report-template'
            ],
            '<pluginHandle:sprout-reports>/<pluginSection:reports>/<dataSourceId:\d+>/edit/<reportId:\d+>' => [
                'route' => 'sprout-base-reports/reports/edit-report-template'
            ],
            '<pluginHandle:sprout-reports>/<pluginSection:reports>/view/<reportId:\d+>' => [
                'route' => 'sprout-base-reports/reports/results-index-template'
            ],
            '<pluginHandle:sprout-reports>/<pluginSection:reports>' => [
                'route' => 'sprout-base-reports/reports/reports-index-template'
            ],
            '<pluginHandle:sprout-reports>' => [
                'route' => 'sprout-base-reports/reports/reports-index-template',
                'params' => [
                    'pluginSection' => 'reports'
                ]
            ],

            // Data Sources
            '<pluginHandle:sprout-reports>/datasources' => [
                'route' => 'sprout-reports/data-sources/data-sources-index-template'
            ],

            // Settings
            'sprout-reports/settings' =>
                'sprout/settings/edit-settings',
            'sprout-reports/settings/general' =>
                'sprout/settings/edit-settings'
        ];
    }
}

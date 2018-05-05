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

use barrelstrength\sproutbase\sproutbase\base\BaseSproutTrait;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\widgets\Number as NumberWidget;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomTwigTemplate;
use barrelstrength\sproutreports\models\Settings;
use barrelstrength\sproutbase\sproutreports\services\DataSources;
use barrelstrength\sproutbase\SproutBaseHelper;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomQuery;
use barrelstrength\sproutreports\services\App;
use Craft;
use craft\base\Plugin;
use barrelstrength\sproutreports\variables\SproutReportsVariable;
use craft\db\Migration;
use craft\db\Query;
use craft\helpers\UrlHelper;
use craft\services\Dashboard;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use craft\events\RegisterComponentTypesEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\services\UserPermissions;
use craft\events\RegisterUserPermissionsEvent;

/**
 * https://craftcms.com/docs/plugins/introduction
 *
 *
 * @author    Barrelstrength
 * @package   SproutReports
 * @since     3
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
    public static $pluginId = 'sprout-reports';

    /**
     * @var bool
     */
    public $hasCpSection = true;

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();

        $this->setComponents([
            'app' => App::class
        ]);

        self::$app = $this->get('app');

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = NumberWidget::class;
        });

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            $variable = $event->sender;
            $variable->set('sproutReports', SproutReportsVariable::class);
        });

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {

            $event->rules['sprout-reports/reports'] = 'sprout-base/reports/index';
            $event->rules['sprout-reports/reports/<groupId:\d+>'] = 'sprout-base/reports/index';

            $event->rules['sprout-reports/reports/<dataSourceId>-<dataSourceSlug>/new'] = 'sprout-base/reports/edit-report';
            $event->rules['sprout-reports/reports/<dataSourceId>-<dataSourceSlug>/edit/<reportId:\d+>'] = 'sprout-base/reports/edit-report';

            $event->rules['sprout-reports/datasources'] = ['template' => 'sprout-reports/datasources/index'];

            $event->rules['sprout-reports/reports/view/<reportId:\d+>'] = 'sprout-base/reports/results-index';

            $event->rules['sprout-reports/settings'] = 'sprout-base/settings/edit-settings';
            $event->rules['sprout-reports/settings/general'] = 'sprout-base/settings/edit-settings';
        });

        if (Craft::$app->getEdition() === Craft::Pro) {
            Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {

                $name = Craft::t('sprout-reports', 'Sprout Reports');

                $event->permissions[$name]['sproutReports-editReports'] = ['label' => Craft::t('sprout-reports', 'Edit Reports')];
                $event->permissions[$name]['sproutReports-editDataSources'] = ['label' => Craft::t('sprout-reports', 'Edit Data Sources')];
                $event->permissions[$name]['sproutReports-editSettings'] = ['label' => Craft::t('sprout-reports', 'Edit Plugin Settings')];
            });
        }

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

        return array_merge($parent, [
            'subnav' => [
                'reports' => [
                    'label' => Craft::t('sprout-reports', 'Reports'),
                    'url' => 'sprout-reports/reports'
                ],
                'datasources' => [
                    'label' => Craft::t('sprout-reports', 'Data Sources'),
                    'url' => 'sprout-reports/datasources'
                ],
                'settings' => [
                    'label' => Craft::t('sprout-reports', 'Settings'),
                    'url' => 'sprout-reports/settings/general'
                ]
            ]
        ]);
    }

    /**
     * Performs actions after the plugin is installed.
     *
     * @throws \yii\db\Exception
     */
    protected function afterInstall()
    {
        $dataSourceClasses = [
            CustomQuery::class,
            CustomTwigTemplate::class
        ];

        SproutBase::$app->dataSources->installDataSources($dataSourceClasses);
    }
}

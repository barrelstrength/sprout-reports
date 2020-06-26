<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports;

use barrelstrength\sproutbase\app\reports\datasources\CustomTwigTemplate;
use barrelstrength\sproutbase\app\reports\datasources\Users;
use barrelstrength\sproutbase\app\reports\widgets\Number;
use barrelstrength\sproutbase\app\reports\widgets\Visualization;
use barrelstrength\sproutbase\config\base\SproutBasePlugin;
use barrelstrength\sproutbase\config\configs\ReportsConfig;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbase\SproutBaseHelper;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Dashboard;
use yii\base\Event;

class SproutReports extends SproutBasePlugin
{
    const EDITION_LITE = 'lite';
    const EDITION_PRO = 'pro';

    /**
     * @var string
     */
    public $schemaVersion = '1.5.4';

    /**
     * @var string
     */
    public $minVersionRequired = '1.5.6';

    /**
     * @inheritdoc
     */
    public static function editions(): array
    {
        return [
            self::EDITION_LITE,
            self::EDITION_PRO,
        ];
    }

    public static function getSproutConfigs(): array
    {
        return [
            ReportsConfig::class,
        ];
    }

    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, static function(RegisterComponentTypesEvent $event) {
            $event->types[] = Number::class;
            $event->types[] = Visualization::class;
        });
    }

    /**
     * @inheritDoc
     */
    protected function afterInstall()
    {
        $dataSourceTypes = [
            CustomTwigTemplate::class,
            Users::class,
        ];

        SproutBase::$app->dataSources->installDataSources($dataSourceTypes);
    }
}

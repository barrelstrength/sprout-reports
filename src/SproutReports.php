<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports;

use barrelstrength\sproutbase\app\reports\datasources\CustomQuery;
use barrelstrength\sproutbase\app\reports\datasources\CustomTwigTemplate;
use barrelstrength\sproutbase\app\reports\datasources\Users;
use barrelstrength\sproutbase\config\base\SproutCentralInterface;
use barrelstrength\sproutbase\config\configs\GeneralConfig;
use barrelstrength\sproutbase\config\configs\ReportsConfig;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbase\SproutBaseHelper;
use craft\base\Plugin;

class SproutReports extends Plugin implements SproutCentralInterface
{
    /**
     * @var string
     */
    public $schemaVersion = '1.5.4';

    /**
     * @var string
     */
    public $minVersionRequired = '0.9.3';

    public static function getSproutConfigs(): array
    {
        return [
            GeneralConfig::class,
            ReportsConfig::class
        ];
    }

    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();
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

        SproutBase::$app->dataSources->installDataSources($dataSourceTypes);
    }
}

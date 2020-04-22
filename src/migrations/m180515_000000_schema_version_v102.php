<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m180515_000000_update_datasources_types;
use barrelstrength\sproutbasereports\migrations\m180515_000001_rename_datasources_pluginId_column;
use barrelstrength\sproutbasereports\migrations\m180515_000002_update_report_element_types;
use craft\db\Migration;
use yii\base\NotSupportedException;

/**
 * m180515_000000_schema_version_v102 migration.
 */
class m180515_000000_schema_version_v102 extends Migration
{
    /**
     * @inheritdoc
     *
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $dataSourceTypesMigration = new m180515_000000_update_datasources_types();

        ob_start();
        $dataSourceTypesMigration->safeUp();
        ob_end_clean();

        $dataSourcePluginIdMigration = new m180515_000001_rename_datasources_pluginId_column();

        ob_start();
        $dataSourcePluginIdMigration->safeUp();
        ob_end_clean();

        $updateReportElementTypesMigration = new m180515_000002_update_report_element_types();

        ob_start();
        $updateReportElementTypesMigration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180515_000000_schema_version_v102 cannot be reverted.\n";

        return false;
    }
}

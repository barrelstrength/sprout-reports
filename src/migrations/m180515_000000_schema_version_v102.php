<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m180515_000000_update_datasources_types;
use barrelstrength\sproutbasereports\migrations\m180515_000001_rename_datasources_pluginId_column;
use craft\db\Migration;

/**
 * m180515_000000_schema_version_v102 migration.
 */
class m180515_000000_schema_version_v102 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $dataSourceTypesMigration = new m180515_000000_update_datasources_types();

        ob_start();
        $dataSourceTypesMigration->safeUp();
        ob_end_clean();

        $dataSourcePluginIdMigration = new m180515_000001_rename_datasources_pluginId_column();

        ob_start();
        $dataSourcePluginIdMigration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180515_000000_schema_version_v102 cannot be reverted.\n";
        return false;
    }
}

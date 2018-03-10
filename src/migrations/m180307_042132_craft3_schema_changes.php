<?php

namespace barrelstrength\sproutreports\migrations;

use craft\db\Migration;
use barrelstrength\sproutbase\migrations\sproutreports\m180307_042132_craft3_schema_changes as SproutReportsCraft2toCraft3Migration;

/**
 * m180307_042132_craft3_schema_changes migration.
 */
class m180307_042132_craft3_schema_changes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $migration = new SproutReportsCraft2toCraft3Migration();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180307_042132_craft3_schema_changes cannot be reverted.\n";
        return false;
    }
}

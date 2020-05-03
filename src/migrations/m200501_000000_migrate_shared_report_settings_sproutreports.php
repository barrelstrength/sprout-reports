<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m200501_000000_migrate_shared_report_settings;
use craft\db\Migration;

class m200501_000000_migrate_shared_report_settings_sproutreports extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $migration = new m200501_000000_migrate_shared_report_settings();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200501_000000_migrate_shared_report_settings_sproutreports cannot be reverted.\n";

        return false;
    }
}

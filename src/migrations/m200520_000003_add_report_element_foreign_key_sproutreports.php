<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m200520_000003_add_report_element_foreign_key;
use craft\db\Migration;

class m200520_000003_add_report_element_foreign_key_sproutreports extends Migration
{
    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $migration = new m200520_000003_add_report_element_foreign_key();

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
        echo "m200520_000003_add_report_element_foreign_key_sproutreports cannot be reverted.\n";

        return false;
    }
}

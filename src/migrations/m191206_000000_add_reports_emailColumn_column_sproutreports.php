<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m191206_000000_add_reports_emailColumn_column;
use craft\db\Migration;
use yii\base\NotSupportedException;

/**
 * m191206_000000_add_reports_emailColumn_column_sproutreports migration.
 */
class m191206_000000_add_reports_emailColumn_column_sproutreports extends Migration
{
    /**
     * @inheritdoc
     *
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new m191206_000000_add_reports_emailColumn_column();

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
        echo "m191206_000000_add_reports_emailColumn_column_sproutreports cannot be reverted.\n";
        return false;
    }
}

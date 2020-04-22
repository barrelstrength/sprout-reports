<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m190714_000000_add_context_column;
use craft\db\Migration;
use yii\base\NotSupportedException;

/**
 * m190714_000000_add_context_column_sproutreports migration.
 */
class m190714_000000_add_context_column_sproutreports extends Migration
{
    /**
     * @inheritdoc
     *
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new m190714_000000_add_context_column();

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
        echo "m190714_000000_add_context_column_sproutreports cannot be reverted.\n";

        return false;
    }
}

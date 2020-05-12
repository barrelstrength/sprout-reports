<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m200512_000000_add_reports_delimiter_column;
use craft\db\Migration;
use yii\base\NotSupportedException;

class m200512_000000_add_reports_delimiter_column_sproutreports extends Migration
{
    /**
     * @inheritdoc
     *
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new m200512_000000_add_reports_delimiter_column();

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
        echo "m200512_000000_add_reports_delimiter_column_sproutreports cannot be reverted.\n";

        return false;
    }
}

<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbase\migrations\sproutreports\Install as SproutBaseReportsInstall;
use craft\db\Migration;

class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $migration = new SproutBaseReportsInstall();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }
}
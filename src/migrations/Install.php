<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbase\migrations\sproutreports\Install as SproutBaseReportsInstall;
use craft\db\Migration;
use Craft;

class Install extends Migration
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->runSproutBaseInstall();

        return true;
    }

    // Protected Methods
    // =========================================================================

    protected function runSproutBaseInstall()
    {
        $migration = new SproutBaseReportsInstall();

        ob_start();
        $migration->safeUp();
        ob_end_clean();
    }
}
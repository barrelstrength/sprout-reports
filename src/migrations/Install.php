<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbase\config\base\DependencyInterface;
use barrelstrength\sproutbase\migrations\Install as SproutBaseInstall;
use barrelstrength\sproutbase\app\reports\migrations\Install as SproutBaseReportsInstall;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\SproutReports;
use craft\db\Migration;

class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        SproutBase::$app->config->runInstallMigrations(SproutReports::getInstance());

        return true;
    }

    public function safeDown(): bool
    {
        SproutBase::$app->config->runUninstallMigrations(SproutReports::getInstance());

        return true;
    }
}
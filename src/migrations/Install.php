<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\SproutReports;
use craft\db\Migration;
use ReflectionException;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\web\ServerErrorHttpException;

class Install extends Migration
{
    /**
     * @throws ErrorException
     * @throws Exception
     * @throws NotSupportedException
     * @throws ServerErrorHttpException
     */
    public function safeUp()
    {
        SproutBase::$app->config->runInstallMigrations(SproutReports::getInstance());
    }

    public function safeDown()
    {
        SproutBase::$app->config->runUninstallMigrations(SproutReports::getInstance());
    }
}
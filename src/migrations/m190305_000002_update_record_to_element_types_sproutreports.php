<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutreports\migrations;

use craft\db\Migration;
use barrelstrength\sproutbasereports\migrations\m190305_000002_update_record_to_element_types as BaseUpdateElements;

/**
 * m190305_000002_update_record_to_element_types_sproutreports migration.
 */
class m190305_000002_update_record_to_element_types_sproutreports extends Migration
{
    /**
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function safeUp(): bool
    {
        $migration = new BaseUpdateElements();

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
        echo "m190305_000002_update_record_to_element_types_sproutreports cannot be reverted.\n";
        return false;
    }
}
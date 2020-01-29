<?php

namespace barrelstrength\sproutreports\migrations;

use craft\db\Migration;
use craft\db\Query;

/**
 * m180310_000000_number_widget_type_update migration.
 */
class m180310_000000_number_widget_type_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        /** @noinspection ClassConstantCanBeUsedInspection */
        $numberReportClass = 'barrelstrength\sproutreports\widgets\Number';

        $numberWidgets = (new Query())
            ->select(['id'])
            ->from(['{{%widgets}}'])
            ->where(['type' => 'SproutReports_NumberReport'])
            ->all();

        foreach ($numberWidgets as $key => $id) {
            $this->update('{{%widgets}}', [
                'type' => $numberReportClass
            ], [
                'id' => $id
            ], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180310_000000_number_widget_type_update cannot be reverted.\n";

        return false;
    }
}

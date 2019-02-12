<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutreports\migrations;

use craft\db\Migration;

/**
 * m191202_000002_update_report_element_types migration.
 */
class m191202_000002_update_report_element_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $seedClasses = [
            0 => [
                'oldType' => 'barrelstrength\sproutbase\app\reports\elements\Report',
                'newType' => 'barrelstrength\sproutbasereports\elements\Report'
            ]
        ];

        foreach ($seedClasses as $seedClass) {
            $this->update('{{%elements}}', [
                'type' => $seedClass['newType']
            ], ['type' => $seedClass['oldType']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m191202_000002_update_report_element_types cannot be reverted.\n";
        return false;
    }
}

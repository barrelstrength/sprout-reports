<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomQuery;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomTwigTemplate;
use craft\db\Migration;

/**
 * m180307_042132_sprout_reports_report migration.
 */
class m180307_042132_sprout_reports_report extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('{{%sproutreports_reports}}', 'options', 'settings');
        $this->addColumn('{{%sproutreports_datasources}}', 'type', $this->string()->after('id'));
        $dataSourceClasses = [
            CustomQuery::class,
            CustomTwigTemplate::class
        ];

        SproutBase::$app->dataSources->installDataSources($dataSourceClasses);

        $this->dropColumn('{{%sproutreports_datasources}}', 'dataSourceId');
        $this->dropColumn('{{%sproutreports_datasources}}', 'options');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180307_042132_SproutReports cannot be reverted.\n";
        return false;
    }
}

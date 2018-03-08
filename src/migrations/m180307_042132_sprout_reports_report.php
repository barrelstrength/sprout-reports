<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomQuery;
use barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomTwigTemplate;
use craft\db\Migration;
use craft\db\Query;
use barrelstrength\sproutreportscategories\integrations\sproutreports\datasources\Categories;
use barrelstrength\sproutreportsusers\integrations\sproutreports\datasources\Users;
use Craft;

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

        if (Craft::$app->getPlugins()->getPlugin('sprout-reports-users')) {
            $dataSourceClasses[] = Users::class;
        }

        if (Craft::$app->getPlugins()->getPlugin('sprout-reports-categories')) {
            $dataSourceClasses[] = Categories::class;
        }

        SproutBase::$app->dataSources->installDataSources($dataSourceClasses);

        $this->dropColumn('{{%sproutreports_datasources}}', 'dataSourceId');
        $this->dropColumn('{{%sproutreports_datasources}}', 'options');

        $this->addColumn('{{%sproutreports_reports}}', 'hasNameFormat', $this->integer()->after('name'));

        $this->updateChangedDataSourceId();

        return true;
    }

    private function updateChangedDataSourceId()
    {
        $sources = [
            'sproutreports.query'      => CustomQuery::class,
            'sproutreports.users'      => Users::class,
            'sproutreports.categories' => Categories::class
        ];

        if (Craft::$app->getPlugins()->getPlugin('sprout-reports-users')) {
            $sources['sproutreports.users'] = Users::class;
        }

        if (Craft::$app->getPlugins()->getPlugin('sprout-reports-categories')) {
            $sources['sproutreports.categories'] = Categories::class;
        }

        $query     = new Query();
        foreach ($sources as $key => $namespace) {
            $id = $query->select(['id'])
                ->from(['{{%sproutreports_datasources}}'])
                ->where(['type' => $namespace])
                ->scalar();

            $this->update('{{%sproutreports_reports}}', ['dataSourceId' => $id], ['dataSourceId' => $key]);
        }
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

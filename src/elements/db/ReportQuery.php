<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutreports\elements\db;


use craft\elements\db\ElementQuery;
use craft\helpers\Db;

use barrelstrength\sproutreports\SproutReports;

class ReportQuery extends ElementQuery
{
    public $id;

    public $name;

    public $hasNameFormat;

    public $nameFormat;

    public $handle;

    public $description;

    public $allowHtml;

    public $settings;

    public $pluginId;

    public $dataSourceId;

    public $dataSourceSlug;

    public $enabled;

    public $groupId;

    public $dateCreated;

    public $dateUpdated;

    public $results;

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('sproutreports_reports');
        $this->query->select([
            'sproutreports_reports.dataSourceId',
            'sproutreports_reports.name',
            'sproutreports_reports.hasNameFormat',
            'sproutreports_reports.nameFormat',
            'sproutreports_reports.handle',
            'sproutreports_reports.description',
            'sproutreports_reports.allowHtml',
            'sproutreports_reports.settings',
            'sproutreports_reports.groupId',
            'sproutreports_reports.enabled',
            'sproutreports_datasources.pluginId'
        ]);

        $this->query->innerJoin('{{%sproutreports_datasources}} sproutreports_datasources', "[[sproutreports_datasources.id]] = [[sproutreports_reports.dataSourceId]]");

        if ($this->pluginId) {
            $this->query->andWhere(Db::parseParam(
                'sproutreports_datasources.pluginId', $this->pluginId)
            );
        }

        if ($this->dataSourceId) {
            $this->query->andWhere(Db::parseParam(
                'sproutreports_reports.dataSourceId', $this->dataSourceId)
            );
        }
        
        if ($this->groupId) {
            $this->query->andWhere(Db::parseParam(
                'sproutreports_reports.groupId', $this->groupId)
            );
        }

        return parent::beforePrepare();
    }
}
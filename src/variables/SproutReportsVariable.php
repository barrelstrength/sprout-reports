<?php

namespace barrelstrength\sproutreports\variables;

use barrelstrength\sproutbase\models\sproutreports\Report;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\SproutReports;

class SproutReportsVariable
{
    public function getDataSources()
    {
        return SproutBase::$app->dataSources->getAllDataSources();
    }

    /**
     * @return null|Report[]
     */
    public function getReports()
    {
        return SproutBase::$app->reports->getAllReports();
    }

    /**
     * @return null|Report[]
     */
    public function getReportGroups()
    {
        return SproutBase::$app->reportGroups->getAllReportGroups();
    }

    /**
     * @param $groupId
     *
     * @return array
     * @throws \yii\base\Exception
     */
    public function getReportsByGroupId($groupId)
    {
        return SproutBase::$app->reports->getReportsByGroupId($groupId);
    }

    /**
     * @param array $row
     */
    public function addHeaderRow(array $row)
    {
        SproutReports::$app->twigDataSource->addHeaderRow($row);
    }

    /**
     * Add a single row of data to your report
     *
     * @param array $row
     */
    public function addRow(array $row)
    {
        SproutReports::$app->twigDataSource->addRow($row);
    }

    /**
     * Add multiple rows of data to your report
     *
     * @example array(
     *   array( ... ),
     *   array( ... )
     * )
     *
     * @param array $rows
     */
    public function addRows(array $rows)
    {
        SproutReports::$app->twigDataSource->addRows($rows);
    }
}
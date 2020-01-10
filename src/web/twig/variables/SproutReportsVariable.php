<?php

namespace barrelstrength\sproutreports\web\twig\variables;

use barrelstrength\sproutbasereports\elements\Report;
use barrelstrength\sproutbasereports\SproutBaseReports;
use barrelstrength\sproutreports\SproutReports;

class SproutReportsVariable
{
    /**
     * @return Report[]
     */
    public function getReports(): array
    {
        return SproutBaseReports::$app->reports->getAllReports();
    }

    /**
     * @return null|Report[]
     */
    public function getReportGroups()
    {
        return SproutBaseReports::$app->reportGroups->getReportGroups();
    }

    /**
     * @param $groupId
     *
     * @return array
     * @throws \yii\base\Exception
     */
    public function getReportsByGroupId($groupId): array
    {
        return SproutBaseReports::$app->reports->getReportsByGroupId($groupId);
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
     * @param array $rows
     *
     * @example array(
     *          array( ... ),
     *          array( ... )
     *          )
     *
     */
    public function addRows(array $rows)
    {
        SproutReports::$app->twigDataSource->addRows($rows);
    }
}
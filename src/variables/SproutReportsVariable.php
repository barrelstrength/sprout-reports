<?php

namespace barrelstrength\sproutreports\variables;

use barrelstrength\sproutbase\models\sproutreports\Report;
use barrelstrength\sproutbase\SproutBase;

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
     * @return null|Report[]
     */
    public function getReportsByGroupId($groupId)
    {
        return SproutBase::$app->reports->getReportsByGroupId($groupId);
    }
}
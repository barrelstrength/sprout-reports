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
		return SproutReports::$app->reports->getAllReports();
	}

	/**
	 * @return null|Report[]
	 */
	public function getReportGroups()
	{
		return SproutReports::$app->reportGroups->getAllReportGroups();
	}

	/**
	 * @param $groupId
	 *
	 * @return null|Report[]
	 */
	public function getReportsByGroupId($groupId)
	{
		return SproutReports::$app->reports->getReportsByGroupId($groupId);
	}
}
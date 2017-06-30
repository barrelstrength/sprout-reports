<?php

namespace barrelstrength\sproutreports\variables;

use barrelstrength\sproutcore\models\sproutreports\Report;
use barrelstrength\sproutcore\SproutCore;
use barrelstrength\sproutreports\SproutReports;

class SproutReportsVariable
{
	public function getDataSources()
	{
		return SproutCore::$app->dataSources->getAllDataSources();
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
<?php

namespace barrelstrength\sproutreports\variables;

use barrelstrength\sproutreports\models\Report;
use barrelstrength\sproutreports\SproutReports;

class SproutReportsVariable
{
	public function getDataSources()
	{
		return SproutReports::$api->dataSources->getAllDataSources();
	}

	/**
	 * @return null|Report[]
	 */
	public function getReports()
	{
		return SproutReports::$api->reports->getAllReports();
	}

	/**
	 * @return null|Report[]
	 */
	public function getReportGroups()
	{
		return SproutReports::$api->reportGroups->getAllReportGroups();
	}

	/**
	 * @param $groupId
	 *
	 * @return null|Report[]
	 */
	public function getReportsByGroupId($groupId)
	{
		return SproutReports::$api->reports->getReportsByGroupId($groupId);
	}
}
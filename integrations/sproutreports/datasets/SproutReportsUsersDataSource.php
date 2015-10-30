<?php
namespace Craft;

class SproutReportsUsersDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Users');
	}

	public function getDescription()
	{
		return Craft::t('Returns a subset of users in your Craft install');
	}

	public function getResults(SproutReports_ReportModel &$report)
	{
		return array();
	}
}

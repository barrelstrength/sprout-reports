<?php
namespace Craft;

class SproutReportsEntriesDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Entries');
	}

	public function getDescription()
	{
		return Craft::t('Returns a subset of entries in your Craft install');
	}

	public function getResults(SproutReports_ReportModel &$report)
	{
		return array();
	}
}

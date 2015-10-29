<?php
namespace Craft;

class SproutReportsEntriesDataSet extends SproutReportsBaseDataSet
{
	public function getName()
	{
		return Craft::t('Entries');
	}

	public function getProviderName()
	{
		return Craft::t('Sprout Reports');
	}

	public function getDescription()
	{
		return Craft::t('Returns a subset of entries in your Craft install');
	}

	public function getResultSet(SproutReportsBaseReportModel $report)
	{
		return array();
	}
}

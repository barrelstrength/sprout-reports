<?php
namespace Craft;

/**
 * Class SproutReportsCustomQueryDataSource
 *
 * @package Craft
 */
class SproutReportsCustomQueryDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Custom Query');
	}

	public function getDescription()
	{
		return Craft::t('Returns whatever you tell it to, you just have to know how to write SQL');
	}

	public function getResults(SproutReports_ReportModel &$report)
	{
		$query = $report->getOption('query');

		try
		{
			return craft()->db->createCommand($query)->queryAll();
		}
		catch (\Exception $e)
		{
			$report->setResultsError($e->getMessage());
		}
	}

	public function getOptionsHtml(array $options = array())
	{
		return craft()->templates->render('sproutreports/reports/_options/custom-query', compact('options'));
	}
}

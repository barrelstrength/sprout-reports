<?php
namespace Craft;

/**
 * Class SproutReportsQueryDataSource
 *
 * @package Craft
 */
class SproutReportsQueryDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Custom Query');
	}

	/**
	 * @return null|string
	 */
	public function getDescription()
	{
		return Craft::t('Allows you to create reports based on a custom SQL statement.');
	}

	/**
	 * @todo:so Let's bring back a little sanity checks back into raw queries
	 *
	 * @param SproutReports_ReportModel $report
	 *
	 * @return \CDbDataReader
	 */
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

	/**
	 * @param array $options
	 *
	 * @return string
	 */
	public function getOptionsHtml(array $options = array())
	{
		return craft()->templates->render('sproutreports/_reports/options/query', compact('options'));
	}
}

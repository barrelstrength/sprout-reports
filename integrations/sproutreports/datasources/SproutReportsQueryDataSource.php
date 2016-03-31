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
		return Craft::t('Create reports using a custom database query');
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
		$optionErrors = $this->report->getErrors('options');
		$optionErrors = array_shift($optionErrors);

		return craft()->templates->render('sproutreports/datasources/_options/query', array(
			'options' => count($options) ? $options : $this->report->getOptions(),
			'errors' => $optionErrors
		));
	}

	/**
	 * @param array $options
	 * @return bool
	 */
	public function validateOptions(array $options = array(), array &$errors = array())
	{
		if (empty($options['query']))
		{
			$errors['query'][] = Craft::t('Query cannot be blank.');

			return false;
		}

		return true;
	}
}

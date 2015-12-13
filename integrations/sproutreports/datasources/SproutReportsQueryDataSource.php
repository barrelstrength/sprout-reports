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
		$optionErrors = array_shift($this->report->getErrors('options'));

		return craft()->templates->render('sproutreports/datasources/_options/query', array(
			'options' => $this->report->getOptions(),
			'errors' => $optionErrors
		));
	}

	/**
	 * Validate our data source options
	 *
	 * @param array $options
	 * @return array|bool
	 */
	public function validate(array $options = array())
	{
		$errors = null;

		if (empty($options['query']))
		{
			$errors['query'][] = Craft::t('Query cannot be blank.');

			return $errors;
		}

		return true;
	}
}

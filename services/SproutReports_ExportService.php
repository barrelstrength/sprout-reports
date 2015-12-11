<?php
namespace Craft;

use League\Csv\Writer;

class SproutReports_ExportService extends BaseApplicationComponent
{
	/**
	 * Initializes the service by importing vendor libraries
	 */
	public function init()
	{
		parent::init();

		require_once dirname(__FILE__).'/../vendor/autoload.php';
	}

	/**
	 * @param array $values
	 * @param array $labels
	 * @param array $variables
	 *
	 * @return string
	 */
	public function toHtml(array &$values, array $labels = array(), array $variables = array())
	{
		if (empty($labels))
		{
			$labels = array_keys($values[0]);
		}

		$variables['values'] = $values;
		$variables['labels'] = $labels;

		return craft()->templates->render('sproutreports/results/index', $variables);
	}

	/**
	 * @param array $values
	 *
	 * @throws Exception
	 * @return string
	 */
	public function toJson(array &$values)
	{
		$json = json_encode($values);

		if (json_last_error())
		{
			throw new Exception(json_last_error_msg());
		}

		return $json;
	}

	/**
	 * Takes an array of values and options labels and creates a downloadable CSV file
	 *
	 * @param array  $values
	 * @param array  $labels
	 * @param string $filename
	 */
	public function toCsv(array &$values, array $labels = array(), $filename = 'export.csv')
	{
		$filename = str_replace('.csv', '', $filename).'.csv';

		if (empty($labels))
		{
			$labels = array_keys($values[0]);
		}

		$csv = Writer::createFromFileObject(new \SplTempFileObject());

		$csv->insertOne($labels);
		$csv->insertAll($values);
		$csv->output($filename);

		exit(0);
	}
}

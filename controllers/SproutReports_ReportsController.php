<?php
namespace Craft;

use Paste;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Spreadsheet\Worksheet;

class SproutReports_ReportsController extends BaseController
{
	/**
	 * Saves a report query to the database
	 */
	public function actionSaveReport()
	{
		$this->requirePostRequest();

		$report = sproutReports()->reports->prepareFromPost();

		if (sproutReports()->reports->saveReport($report))
		{
			craft()->userSession->setNotice(Craft::t('Report saved.'));
			$this->redirectToPostedUrl($report);
		}
		else
		{
			craft()->userSession->setError(Craft::t('Could not save report.'));

			craft()->urlManager->setRouteVariables(array(
				'report' => $report
			));
		}
	}

	// @todo - reconsider logic
	public function actionEditReport(array $variables = array())
	{
		// If we have a Report Model in our $variables, we are handling errors
		if (isset($variables['report']))
		{
			$variables['report']     = $variables['report'];
			$variables['dataSource'] = $variables['report']->getDataSource();
		}
		elseif (isset($variables['reportId']) && ($report = sproutReports()->reports->get($variables['reportId'])))
		{
			$variables['report']     = $report;
			$variables['dataSource'] = $report->getDataSource();
		}
		else
		{
			$variables['report']               = new SproutReports_ReportModel();
			$variables['report']->dataSourceId = $variables['plugin'] . '.' . $variables['dataSourceKey'];
			$variables['dataSource']           = $variables['report']->getDataSource();
		}

		$this->renderTemplate('sproutreports/reports/_edit', $variables);
	}

	public function actionResultsIndex(array $variables = array())
	{
		$id     = isset($variables['reportId']) ? $variables['reportId'] : null;
		$report = sproutReports()->reports->get($id);

		if ($report)
		{
			$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);
			$labels     = $dataSource->getDefaultLabels();

			$variables['report'] = $report;
			$variables['labels'] = $labels;
			$variables['values'] = array();

			if ($dataSource)
			{
				$values = $dataSource->getResults($report);

				// @todo - reconsider this logic
				if (empty($labels) && !empty($values))
				{
					$labels              = array_keys(array_shift(array_values($values)));
					$variables['labels'] = $labels;
				}

				$variables['values'] = $values;
			}

			// @todo Hand off to the export service when a blank page and 404 issues are sorted out
			return $this->renderTemplate('sproutreports/results/index', $variables);
		}

		throw new HttpException(404, Craft::t('Report not found.'));
	}

	/*
	 * Process report query and display results
	 */
	public function actionResults()
	{
		$reportId  = craft()->request->getSegment(3);
		$report    = craft()->sproutReports_reports->getReportById($reportId);
		$runReport = craft()->request->getParam('runReport');

		$results = array();

		$userValues = array();
		//prepare default values
		foreach ($report->settings as $optionName => $option)
		{
			$userValues[$optionName] = '';
			if (!$runReport)
			{
				if (isset($option['defaultValue']['isSQL']) && ($option['defaultValue']['isSQL'] === true))
				{
					$userValues[$optionName] = craft()->db->createCommand($option['defaultValue']['value'])->queryScalar();
				}
				else
				{
					$userValues[$optionName] = $option['defaultValue']['value'];
				}

				if ($option['type'] == 'date')
				{
					if (!empty($userValues[$optionName]))
					{
						$dateValue = $userValues[$optionName];
					}
					elseif ($optionName == 'dateCreatedFrom')
					{
						$dateValue = date('Y-m-1 00:00:00');
					}
					elseif ($optionName == 'dateCreatedTill')
					{
						$dateValue = date('Y-m-t 23:59:59');
					}
					else
					{
						$dateValue = date('Y-m-d H:i:s');
					}
					$userValues[$optionName] = DateTime::createFromFormat('Y-m-d H:i:s', $dateValue);
				}
			}
			else
			{
				if ($optionDate = craft()->request->getPost('reportOptions.' . $optionName . '.date'))
				{
					$optionTime              = craft()->request->getPost('reportOptions.' . $optionName . '.time') ?: '0:00 AM';
					$userValues[$optionName] = DateTime::createFromFormat('n/j/Yg:i A', $optionDate . $optionTime);
				}
				else
				{
					$userValues[$optionName] = craft()->request->getPost('reportOptions.' . $optionName);
				}
			}
		}

		if ($runReport)
		{
			$reportOptions = craft()->request->getPost('reportOptions');
			$results       = sproutReports()->reports->runReport($report, $reportOptions);

			if ($results->rowCount && craft()->request->getPost('exportCSV'))
			{
				$this->exportDataToCsv($report, $results);
			}
		}

		$this->renderTemplate('sproutreports/results/index', array(
			'report'     => $report,
			'results'    => $results,
			'userValues' => $userValues
		));
	}

	/**
	 * Runs a previously saved report query
	 *
	 * @return mixed  Report output or report output as CSV
	 */
	public function actionRunReport()
	{
		$reportId = craft()->request->getPost('reportId');
		$report   = sproutReports()->reports->getReportById($reportId);
		$results  = sproutReports()->reports->runReport($report);

		if (false !== $results)
		{
			// Export to CSV
			if (craft()->request->getPost('exportData'))
			{
				$this->exportDataToCsv($report, $results);
			}
			else
			{
				craft()->urlManager->setRouteVariables(
					array(
						'report'  => $report,
						'results' => $results
					)
				);
			}
		}
		else
		{
			craft()->userSession->setFlash(
				'errorMessage',
				'Report could not be ran, please [update query]sproutreports/reports/edit/' . $reportId
			);

			craft()->userSession->setFlash('unsavedReport', $report);

			$this->redirect('sproutreports/reports/edit/' . $reportId);
		}

		$this->renderTemplate('results/index', array(
			'report'  => $report,
			'results' => $results
		));
	}

	public function actionDeleteReport()
	{
		$this->requirePostRequest();

		$reportId = craft()->request->getRequiredPost('reportId');

		if ($record = SproutReports_ReportRecord::model()->findById($reportId))
		{
			$record->delete();

			craft()->userSession->setNotice('Report deleted.');

			$this->redirectToPostedUrl($record->getAttributes());
		}

		throw new Exception(Craft::t('Report not found.'));
	}

	public function actionExportReport()
	{
		$id     = craft()->request->getParam('reportId');
		$report = sproutReports()->reports->get($id);

		if ($report)
		{
			$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);

			if ($dataSource)
			{
				$filename = $report->name;
				$values   = $dataSource->getResults($report);
				$labels   = $dataSource->getDefaultLabels();

				sproutReports()->exports->toCsv($values, $labels, $filename);
			}
		}
	}

	/**
	 * Export Data as CSV
	 *
	 * @param  object $results Results from SQL query
	 * @return buffer        The CSV output
	 */
	protected function exportDataToCsv($report, $results)
	{
		$worksheet = new Worksheet();

		foreach ($results as $key => $row)
		{
			if ($key == 0)
			{
				$columnNames = array_keys($row);
				$worksheet->insertRecord($columnNames);
			}

			$worksheet->insertRecord($row);
		}

		$excel = new SimpleExcel();
		$excel->insertWorksheet($worksheet);

		$reportName = str_replace(' ', '', $report['name']);
		$filename   = $reportName . '-' . date('Ymd-hms') . '.csv';

		$excel->exportFile('php://output', 'CSV', array('filename' => $filename));
	}
}

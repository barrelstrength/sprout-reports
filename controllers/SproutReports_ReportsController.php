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

		$report = craft()->request->getPost();
		$report = SproutReports_ReportModel::populateModel($report);

		if (craft()->sproutReports_reports->saveReport($report))
		{
			craft()->userSession->setNotice(Craft::t('Report saved.'));
			$this->redirectToPostedUrl($report);
		}
		else
		{
			craft()->userSession->setError(Craft::t('Could not save report.'));

			craft()->urlManager->setRouteVariables(
				array(
					'errorMessage'	=> $report->getError('customQuery'),
					'unsavedReport' => $report
				)
			);
		}
	}

	/**
	 * Runs a previously saved report query
	 * 
	 * @return mixed	Report output or report output as CSV
	 */
	public function actionRunReport()
	{ 

		$reportId	= craft()->request->getPost('reportId');
		$report		= craft()->sproutReports_reports->getReportById($reportId);
		$results	= craft()->sproutReports_reports->runReport($report['customQuery']);

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
						'report'	=> $report, 
						'results'	=> $results
					)
				);
			}
		}
		else
		{
			craft()->userSession->setFlash(
				'errorMessage',
				'Report could not be ran, please [update query]sproutreports/reports/edit/'.$reportId
			);

			craft()->userSession->setFlash('unsavedReport', $report);

			$this->redirect('sproutreports/reports/edit/'.$reportId);
		}
	}

	public function actionDeleteReport()
	{
		$this->requirePostRequest();

		$reportId = craft()->request->getRequiredPost('id');

		craft()->sproutReports_reports->deleteReportById($reportId);
		$this->redirectToPostedUrl();
	}

	/**
	 * Export Data as CSV
	 * 
	 * @param  object	$results	Results from SQL query
	 * @return buffer				The CSV output
	 */
	protected function exportDataToCsv($report, $results)
	{
		$worksheet = new Worksheet();

		foreach($results as $key => $row) 
		{
			if($key == 0)
			{
				$columnNames = array_keys($row);
				$worksheet->insertRecord($columnNames);
			}

			$worksheet->insertRecord($row);
		}

		$excel = new SimpleExcel();
		$excel->insertWorksheet($worksheet);

		$reportName	= str_replace(' ', '', $report['name']);
		$filename	= $reportName . '-'. date('Ymd-hms') . '.csv';

		$excel->exportFile('php://output', 'CSV', array('filename' => $filename));
	}
}

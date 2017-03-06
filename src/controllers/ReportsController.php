<?php
namespace barrelstrength\sproutreports\controllers;

use Craft;
use craft\web\Controller;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutreports\models\Report;
use barrelstrength\sproutreports\records\Report as ReportRecord;

class ReportsController extends Controller
{
	public function actionIndex($groupId = null)
	{
		return $this->renderTemplate('sproutreports/reports/index', [
			'groupId' => $groupId
		]);
	}
	/**
	 * Saves a report query to the database
	 */
	public function actionSaveReport()
	{
		$this->requirePostRequest();

		$report = SproutReports::$api->reports->prepareFromPost();

		if (!SproutReports::$api->reports->saveReport($report))
		{
			Craft::$app->getSession()->setError(SproutReports::t('Couldn’t save report.'));

			// Send the section back to the template
			Craft::$app->getUrlManager()->setRouteParams([
				'report' => $report
			]);

			return null;
		}

		Craft::$app->getSession()->setNotice(SproutReports::t('Report saved.'));

		return $this->redirectToPostedUrl($report);
/*

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
		}*/
	}
	//
	///**
	// * Saves a report query to the database
	// */
	//public function actionUpdateReport()
	//{
	//	$this->requirePostRequest();
	//
	//	$reportId = craft()->request->getPost('reportId');
	//	$options  = craft()->request->getPost('options');
	//
	//	if ($reportId && $options)
	//	{
	//		$reportModel = sproutReports()->reports->getReport($reportId);
	//
	//		if (!$reportModel)
	//		{
	//			throw new Exception(Craft::t('No report exists with the id “{id}”', array('id' => $reportId)));
	//		}
	//
	//		$reportModel->options = is_array($options) ? $options : array();
	//
	//		if (sproutReports()->reports->saveReport($reportModel))
	//		{
	//			craft()->userSession->setNotice(Craft::t('Query updated.'));
	//			$this->redirectToPostedUrl($reportModel);
	//		}
	//	}
	//
	//	craft()->userSession->setError(Craft::t('Could not update report.'));
	//
	//	$this->redirectToPostedUrl();
	//}
	//
	// @todo - reconsider logic
	public function actionEditReport(string $pluginId, string $dataSourceKey, Report $report = null, int $reportId = null)
	{
		$variables = array();

		$variables['report'] = new Report();

		if (isset($report))
		{
			$variables['report'] = $report;
		}

		if ($reportId != null)
		{
			$reportModel = SproutReports::$api->reports->getReport($reportId);

			$variables['report'] = $reportModel;
		}

		$variables['report']->dataSourceId = $pluginId . '.' . $dataSourceKey;
		$variables['dataSource']           = $variables['report']->getDataSource();

		$variables['continueEditingUrl']   = $variables['dataSource']->getUrl() . '/edit/{id}';

		//\Craft::dd($variables);
/*		// If we have a Report Model in our $variables, we are handling errors
		if (isset($variables['report']))
		{
			$variables['report']     = $report;
			$variables['dataSource'] = $variables['report']->getDataSource();
		}
		elseif (isset($variables['reportId']) && ($report = sproutReports()->reports->getReport($variables['reportId'])))
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
*/
	//	\Craft::dd($variables);
		return $this->renderTemplate('sproutreports/reports/_edit', $variables);
	}

	//public function actionResultsIndex(array $variables = array())
	//{
	//	$reportId = isset($variables['reportId']) ? $variables['reportId'] : null;
	//	$report   = sproutReports()->reports->getReport($reportId);
	//
	//	$options = craft()->request->getPost('options');
	//	$options = count($options) ? $options : array();
	//
	//	if ($report)
	//	{
	//		$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);
	//		$labels     = $dataSource->getDefaultLabels($report, $options);
	//
	//		$variables['dataSource'] = null;
	//		$variables['report']     = $report;
	//		$variables['values']     = array();
	//		$variables['options']    = $options;
	//
	//		if ($dataSource)
	//		{
	//			$values = $dataSource->getResults($report, $options);
	//
	//			if (empty($labels) && !empty($values))
	//			{
	//				$firstItemInArray = reset($values);
	//				$labels           = array_keys($firstItemInArray);
	//			}
	//
	//			$variables['labels']     = $labels;
	//			$variables['values']     = $values;
	//			$variables['dataSource'] = $dataSource;
	//		}
	//
	//		// @todo Hand off to the export service when a blank page and 404 issues are sorted out
	//		return $this->renderTemplate('sproutreports/results/index', $variables);
	//	}
	//
	//	throw new HttpException(404, Craft::t('Report not found.'));
	//}
	//
	public function actionDeleteReport()
	{
		$this->requirePostRequest();

		$reportId = Craft::$app->getRequest()->getBodyParam('reportId');

		if ($record = ReportRecord::findOne($reportId))
		{
			$record->delete();

			Craft::$app->getSession()->setNotice(SproutReports::t('Report deleted.'));

			return $this->redirectToPostedUrl($record);
		}
		else
		{
			throw new \Exception(SproutReports::t('Report not found.'));
		}
	}
	//
	//public function actionExportReport()
	//{
	//	$reportId = craft()->request->getParam('reportId');
	//	$report   = sproutReports()->reports->getReport($reportId);
	//
	//	$options = craft()->request->getPost('options');
	//	$options = count($options) ? $options : array();
	//
	//	if ($report)
	//	{
	//		$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);
	//
	//		if ($dataSource)
	//		{
	//			$date = date("Ymd-his");
	//
	//			$filename = $report->name . '-' . $date;
	//			$labels   = $dataSource->getDefaultLabels($report, $options);
	//			$values   = $dataSource->getResults($report, $options);
	//
	//			sproutReports()->exports->toCsv($values, $labels, $filename);
	//		}
	//	}
	//}
	//
	///**
	// * Export Data as CSV
	// *
	// * @param  object $results Results from SQL query
	// *
	// * @return buffer        The CSV output
	// */
	//protected function exportDataToCsv($report, $results)
	//{
	//	$worksheet = new Worksheet();
	//
	//	foreach ($results as $key => $row)
	//	{
	//		if ($key == 0)
	//		{
	//			$columnNames = array_keys($row);
	//			$worksheet->insertRecord($columnNames);
	//		}
	//
	//		$worksheet->insertRecord($row);
	//	}
	//
	//	$excel = new SimpleExcel();
	//	$excel->insertWorksheet($worksheet);
	//
	//	$reportName = str_replace(' ', '', $report['name']);
	//	$filename   = $reportName . '-' . date('Ymd-hms') . '.csv';
	//
	//	$excel->exportFile('php://output', 'CSV', array('filename' => $filename));
	//}
}

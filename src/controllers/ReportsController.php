<?php
namespace barrelstrength\sproutreports\controllers;

use Craft;
use craft\web\Controller;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutreports\models\Report;
use barrelstrength\sproutreports\records\Report as ReportRecord;

class ReportsController extends Controller
{
	/**
	 * @param null $groupId
	 *
	 * @return \yii\web\Response
	 */
	public function actionIndex($groupId = null)
	{
		return $this->renderTemplate('sprout-reports/reports/index', [
			'groupId' => $groupId
		]);
	}

	/**
	 * Saves a report query to the database
	 * @return null|\yii\web\Response
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
	}

	/**
	 * Saves a report query to the database
	 */
	public function actionUpdateReport()
	{
		$this->requirePostRequest();

		$request = Craft::$app->getRequest();

		$reportId = $request->getBodyParam('reportId');
		$options  = $request->getBodyParam('options');

		if ($reportId && $options)
		{
			$reportModel = SproutReports::$api->reports->getReport($reportId);

			if (!$reportModel)
			{
				throw new \Exception(Craft::t('No report exists with the id “{id}”', array('id' => $reportId)));
			}

			$reportModel->options = is_array($options) ? $options : array();

			if (SproutReports::$api->reports->saveReport($reportModel))
			{
				Craft::$app->getSession()->setNotice(SproutReports::t('Query updated.'));

				return $this->redirectToPostedUrl($reportModel);
			}
		}

		Craft::$app->getSession()->setError(SproutReports::t('Could not update report.'));

		return $this->redirectToPostedUrl();
	}

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

		return $this->renderTemplate('sprout-reports/reports/_edit', $variables);
	}

	public function actionResultsIndex($reportId = null)
	{
		$report = SproutReports::$api->reports->getReport($reportId);

		$options = Craft::$app->getRequest()->getBodyParam('options');
		$options = count($options) ? $options : array();

		if ($report)
		{
			$dataSource = SproutReports::$api->dataSources->getDataSourceById($report->dataSourceId);
			$labels     = $dataSource->getDefaultLabels($report, $options);

			$variables['dataSource'] = null;
			$variables['report']     = $report;
			$variables['values']     = array();
			$variables['options']    = $options;
			$variables['reportId']   = $reportId;

			if ($dataSource)
			{
				$values = $dataSource->getResults($report, $options);

				if (empty($labels) && !empty($values))
				{
					$firstItemInArray = reset($values);
					$labels           = array_keys($firstItemInArray);
				}

				$variables['labels']     = $labels;
				$variables['values']     = $values;
				$variables['dataSource'] = $dataSource;
			}

			// @todo Hand off to the export service when a blank page and 404 issues are sorted out
			return $this->renderTemplate('sprout-reports/results/index', $variables);
		}

		throw new \HttpException(404, SproutReports::t('Report not found.'));
	}

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

	public function actionExportReport()
	{
		$reportId = Craft::$app->getRequest()->getParam('reportId');

		$report   = SproutReports::$api->reports->getReport($reportId);

		$options = Craft::$app->getRequest()->getBodyParam('options');
		$options = count($options) ? $options : array();

		if ($report)
		{
			$dataSource = SproutReports::$api->dataSources->getDataSourceById($report->dataSourceId);

			if ($dataSource)
			{
				$date = date("Ymd-his");

				$filename = $report->name . '-' . $date;
				$labels   = $dataSource->getDefaultLabels($report, $options);
				$values   = $dataSource->getResults($report, $options);

				SproutReports::$api->exports->toCsv($values, $labels, $filename);
			}
		}
	}
}

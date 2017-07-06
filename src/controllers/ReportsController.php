<?php
namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutcore\SproutCore;
use Craft;
use craft\web\assets\cp\CpAsset;
use craft\web\Controller;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutcore\models\sproutreports\Report;
use barrelstrength\sproutcore\records\sproutreports\Report as ReportRecord;

class ReportsController extends Controller
{
	/**
	 * @param null $groupId
	 *
	 * @return \yii\web\Response
	 */
	public function actionIndex($groupId = null)
	{
		return $this->renderTemplate('sproutreports/reports/index', [
			'groupId' => $groupId
		]);
	}

	public function actionResultsIndex($reportId = null)
	{
		$report = SproutReports::$app->reports->getReport($reportId);

		$options = Craft::$app->getRequest()->getBodyParam('options');
		$options = count($options) ? $options : array();

		if ($report)
		{
			$dataSource = SproutCore::$app->dataSources->getDataSourceById($report->dataSourceId);

			$dataSource->setReport($report);

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

			$this->getView()->registerAssetBundle(CpAsset::class);

			// @todo Hand off to the export service when a blank page and 404 issues are sorted out
			return $this->renderTemplate('sproutreports/results/index', $variables);
		}

		throw new \HttpException(404, SproutReports::t('Report not found.'));
	}
}

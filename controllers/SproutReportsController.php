<?php
namespace Craft;

class SproutReportsController extends BaseController
{
	public function actionSaveReport()
	{
		Craft::dd($_POST);
	}

	public function actionEditReport(array $variables = array())
	{
		if (isset($variables['id']) && ($report = sproutReports()->getReport($variables['id'])))
		{
			$variables['report']  = $report;
			$variables['dataSet'] = sproutReports()->getDataSet($report->dataSetId);
		}
		else
		{
			$variables['dataSet'] = sproutReports()->getDataSet(craft()->request->getPost('dataSetId'));
		}

		$this->renderTemplate('sproutreports/_harmony/reports/edit', $variables);
	}

	public function actionDeleteReport()
	{
		//
	}

	public function actionRunReport()
	{
		//
	}
}

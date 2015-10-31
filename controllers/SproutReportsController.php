<?php
namespace Craft;

class SproutReportsController extends BaseController
{
	public function actionSaveReport()
	{
		$this->requirePostRequest();
		$this->requireAdmin(); // @todo: Let's think about permission

		$report = sproutReports()->reports->prepareFromPost();

		// Model prepared ok?
		if ($report->hasErrors())
		{
			Craft::dd($report->getErrors());
		}

		// Model validated ok?
		if (!$report->validate())
		{
			Craft::dd($report->getErrors());
		}

		// Model saved ok?
		if (sproutReports()->reports->save($report) && !$report->hasErrors())
		{
			$this->redirectToPostedUrl($report);
		}
		else
		{
			Craft::dd($report->getErrors());
		}
	}

	public function actionEditReport(array $variables = array())
	{
		if (isset($variables['reportId']) && ($report = sproutReports()->reports->get($variables['reportId'])))
		{
			$variables['report']     = $report;
			$variables['dataSource'] = sproutReports()->sources->get($report->dataSourceId);
		}
		else
		{
			$variables['dataSource'] = sproutReports()->sources->get($variables['plugin'].'.'.$variables['dataSourceKey']);
		}

		$this->renderTemplate('sproutreports/_reports/edit', $variables);
	}

	public function actionDeleteReport()
	{
		Craft::dd(array($_POST, $_GET));
	}

	public function actionRunReport()
	{
		$id     = craft()->request->getParam('reportId');
		$report = sproutReports()->reports->get($id);

		if ($report)
		{
			$dataSource = sproutReports()->sources->get($report->dataSourceId);

			if ($dataSource)
			{
				Craft::dd($dataSource->getResults($report));
			}
		}
	}
}

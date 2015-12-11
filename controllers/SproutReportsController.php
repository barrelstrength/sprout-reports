<?php
namespace Craft;

class SproutReportsController extends BaseController
{
	public function actionSaveReport()
	{
		$this->requirePostRequest();
		$this->requireAdmin();

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

		$this->renderTemplate('sproutreports/reports/_edit', $variables);
	}

	public function actionDeleteReport()
	{
		$this->requirePostRequest();
		$this->requireAdmin();

		if (($record = SproutReports_ReportRecord::model()->findById(craft()->request->getPost('id'))))
		{
			$record->delete();

			craft()->userSession->setNotice('Report deleted successfully.');

			$this->redirectToPostedUrl($record->getAttributes());
		}

		throw new Exception(Craft::t('Report not found.'));
	}

	public function actionRunReport(array $variables = array())
	{
		$id     = isset($variables['reportId']) ? $variables['reportId'] : null;
		$report = sproutReports()->reports->get($id);

		if ($report)
		{
			$dataSource = sproutReports()->sources->get($report->dataSourceId);

			if ($dataSource)
			{
				$values = $dataSource->getResults($report);
				$labels = $dataSource->getDefaultLabels();

				if (empty($labels))
				{
					$labels = array_keys($values[0]);
				}

				$variables['values'] = $values;
				$variables['labels'] = $labels;
				$variables['report'] = $report;

				// @todo Hand off to the export service when a blank page and 404 issues are sorted out
				return $this->renderTemplate('sproutreports/results/index', $variables);
			}
		}

		throw new HttpException(404, Craft::t('Report not found.'));
	}

	public function actionExportReport()
	{
		$id     = craft()->request->getParam('reportId');
		$report = sproutReports()->reports->get($id);

		if ($report)
		{
			$dataSource = sproutReports()->sources->get($report->dataSourceId);

			if ($dataSource)
			{
				$filename = $report->name;
				$values   = $dataSource->getResults($report);
				$labels   = $dataSource->getDefaultLabels();

				sproutReports()->exports->toCsv($values, $labels, $filename);
			}
		}
	}
}

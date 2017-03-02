<?php
namespace barrelstrength\sproutreports\services;

use Craft;
use yii\base\Component;
use barrelstrength\sproutreports\models\Report;
use barrelstrength\sproutreports\records\Report as ReportRecord;
use barrelstrength\sproutreports\SproutReports;

class Reports extends Component
{
	/**
	 * Returns a report model populated from saved/POSTed data
	 *
	 * @throws Exception
	 * @return SproutReports_ReportModel
	 */
	public function prepareFromPost()
	{
		$request = Craft::$app->getRequest();

		$reportId = $request->getBodyParam('id');

		if ($reportId && is_numeric($reportId))
		{
			$instance = SproutReports::$api->reports->getReport($reportId);

			if (!$instance)
			{
				$instance->addError('id', Craft::t('Could not find a report with id {reportId}', compact('reportId')));
			}
		}
		else
		{
			$instance = new Report();
		}

		$options = $request->getBodyParam('options');

		$instance->name         = $request->getBodyParam('name');
		$instance->handle       = $request->getBodyParam('handle');
		$instance->description  = $request->getBodyParam('description');
		$instance->options      = is_array($options) ? $options : array();
		$instance->dataSourceId = $request->getBodyParam('dataSourceId');
		$instance->enabled      = $request->getBodyParam('enabled');
		$instance->groupId      = $request->getBodyParam('groupId', null);

		$dataSource = SproutReports::$api->dataSources->getDataSourceById($instance->dataSourceId);

		$instance->allowHtml = $request->getBodyParam('allowHtml', $dataSource->getDefaultAllowHtml());

		return $instance;
	}

	/**
	 * @param $id
	 *
	 * @return SproutReports_ReportModel
	 */
	public function getReport($reportId)
	{
		$reportRecord  = ReportRecord::findOne($reportId);
		$reportModel   = new Report();

		if ($reportRecord != null)
		{
			$reportModel->attributes = $reportRecord->getAttributes();
		}

		return $reportModel;
	}

	/**
	 * @param SproutReports_ReportModel $model
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function saveReport(&$model)
	{
		if (!$model->validate()) {
			Craft::info('Report not saved due to validation error.', __METHOD__);

			return false;
		}

		$isNewReport = !$model->id;

		if (empty($model->id))
		{
			$record = new ReportRecord();
		}
		else
		{
			$record = ReportRecord::findOne($model->id);
		}

		if (!$this->validateOptions($model))
		{
			return false;
		}

		$record->id           = $model->id;
		$record->name         = $model->name;
		$record->handle       = $model->handle;
		$record->description  = $model->description;
		$record->allowHtml    = $model->allowHtml;
		$record->options      = $model->options;
		$record->dataSourceId = $model->dataSourceId;
		$record->enabled      = $model->enabled;
		$record->groupId      = $model->groupId;

		$db = Craft::$app->getDb();
		$transaction = $db->beginTransaction();
		try
		{
			$record->save(false);
			
			$model->id = $record->id;

			$transaction->commit();
		}
		catch (\Exception $e)
		{
			$transaction->rollBack();

			throw $e;
		}

		return true;
	}

	/**
	 * @param $report
	 *
	 * @return bool
	 */
	protected function validateOptions(&$report)
	{
		$errors = array();

		$dataSource = SproutReports::$api->dataSources->getDataSourceById($report->dataSourceId);

		if (!$dataSource->validateOptions($report->options, $errors))
		{
			$report->addError('options', $errors);

			return false;
		}

		return true;
	}
}

<?php
namespace barrelstrength\sproutreports\services;

use barrelstrength\sproutreports\contracts\BaseReport;
use barrelstrength\sproutreports\models\ReportGroup as ReportGroupModel;
use Craft;
use yii\base\Component;
use barrelstrength\sproutreports\models\Report as ReportModel;
use barrelstrength\sproutreports\records\Report as ReportRecord;
use barrelstrength\sproutreports\records\ReportGroup as ReportGroupRecord;
use barrelstrength\sproutreports\SproutReports;

class Reports extends Component
{
	/**
	 * Returns a report model populated from saved/POSTed data
	 *
	 * @return ReportModel
	 */
	public function prepareFromPost()
	{
		$request = Craft::$app->getRequest();

		$reportId = $request->getBodyParam('id');

		if ($reportId && is_numeric($reportId))
		{
			$instance = $this->getReport($reportId);

			if (!$instance)
			{
				$instance->addError('id', Craft::t('Could not find a report with id {reportId}', compact('reportId')));
			}
		}
		else
		{
			$instance = new ReportModel();
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
	 * @return ReportModel
	 */
	public function getReport($reportId)
	{
		$reportRecord  = ReportRecord::findOne($reportId);
		$reportModel   = new ReportModel();

		if ($reportRecord != null)
		{
			$reportModel->attributes = $reportRecord->getAttributes();
		}

		return $reportModel;
	}

	/**
	 * @param ReportModel $model
	 *
	 * @throws \Exception
	 * @return bool
	 */
	public function saveReport(&$model)
	{
		if (!$model->validate()) {
			Craft::info('Report not saved due to validation error.', __METHOD__);

			return false;
		}

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
	protected function validateOptions(ReportModel &$report)
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

	/**
	 * @return null|ReportModel[]
	 */
	public function getAllReports()
	{
		$reportRecords = ReportRecord::find()->all();

		$reports = $this->populateModels($reportRecords);

		return $reports;
	}

	public function registerReports($reports, ReportGroupModel $group)
	{
		if (!is_array($reports))
		{
			$reports = array($reports);
		}

		foreach ($reports as $report)
		{
			if ($report instanceof BaseReport)
			{
				$record = new ReportRecord();

				$record->name         = $report->getName();
				$record->handle       = $report->getHandle();
				$record->description  = $report->getDescription();
				$record->options      = $report->getOptions();
				$record->dataSourceId = $report->getDataSource()->getId();
				$record->enabled      = true;
				$record->groupId      = $group->id;

				if (!$record->save())
				{
					Craft::warning(print_r($record->getErrors(), true), 'sproutReports');
				}
			}
		}
	}

	/**
	 * @param int $groupId
	 *
	 * @return null|ReportModel[]
	 */
	public function getReportsByGroupId($groupId)
	{
		$reports = array();

		$group = ReportGroupRecord::findOne($groupId);

		if ($group != null)
		{
			$reportRecords = $group->getReports()->all();

			$reports = $this->populateModels($reportRecords);
		}

		return $reports;
	}

	/**
	 * Returns the number of reports that have been created based on a given data source
	 *
	 * @param $dataSourceId
	 *
	 * @return int
	 *
	 */
	public function getCountByDataSourceId($dataSourceId)
	{
		return (int) ReportRecord::find()->where(array('dataSourceId' => $dataSourceId))->count();
	}

	public function populateModels(array $records)
	{
		$models = array();

		if (!empty($records))
		{
			foreach ($records as $record)
			{
				$recordAttributes = $record->getAttributes();
				$model = new ReportModel();
				$model->setAttributes($recordAttributes);

				$models[] = $model;
			}
		}

		return $models;
	}
}

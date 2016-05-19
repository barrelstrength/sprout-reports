<?php
namespace Craft;

/**
 * Class SproutReports_ReportsService
 *
 * @package Craft
 */
class SproutReports_ReportsService extends BaseApplicationComponent
{
	/**
	 * @param SproutReports_ReportModel $model
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function saveReport(SproutReports_ReportModel &$model)
	{
		$isNewReport = !$model->id;

		if (empty($model->id))
		{
			$record = new SproutReports_ReportRecord();
		}
		else
		{
			$record = SproutReports_ReportRecord::model()->findById($model->id);
		}

		if (!$this->validateOptions($model))
		{
			return false;
		}

		$record->setAttributes($model->getAttributes(), false);

		if (!$record->validate())
		{
			$model->addErrors($record->getErrors());

			return false;
		}

		if (!$record->save())
		{
			$model->addError('general', Craft::t('Unable to save report.'));

			return false;
		}

		if (!$isNewReport)
		{
			$model->id = $record->id;
		}

		return true;
	}

	/**
	 * Registers one or more reports with our internal tracking system
	 *
	 * @param array|SproutReportsBaseReport $reports
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function registerReports($reports, SproutReports_ReportGroupModel $group)
	{
		if (!is_array($reports))
		{
			$reports = array($reports);
		}

		foreach ($reports as $report)
		{
			if ($report instanceof SproutReportsBaseReport)
			{
				$record = new SproutReports_ReportRecord();

				$record->name         = $report->getName();
				$record->handle       = $report->getHandle();
				$record->description  = $report->getDescription();
				$record->options      = $report->getOptions();
				$record->dataSourceId = $report->getDataSource()->getId();
				$record->enabled      = true;
				$record->groupId      = $group->id;

				if (!$record->save())
				{
					SproutReportsPlugin::log(print_r($record->getErrors(), true), LogLevel::Warning);
				}
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @return SproutReports_ReportModel
	 */
	public function getReport($reportId)
	{
		$result = SproutReports_ReportRecord::model()->findById($reportId);

		if ($result)
		{
			return SproutReports_ReportModel::populateModel($result);
		}

		return null;
	}

	/**
	 * Returns a SproutReports_ReportModel model if one is found in the database by handle
	 *
	 * @param string $handle
	 * @return false|SproutReports_ReportModel
	 */
	public function getReportByHandle($handle)
	{
		$result = craft()->db->createCommand()
			->select('*')
			->from('sproutreports_reports')
			->where('handle = :handle', array(
				':handle' => $handle
			))
			->queryRow();

		if ($result)
		{
			return SproutReports_ReportModel::populateModel($result);
		}
	}

	/**
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getAllReports()
	{
		$result = SproutReports_ReportRecord::model()->findAll();

		if ($result)
		{
			return SproutReports_ReportModel::populateModels($result);
		}
	}

	/**
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getEnabledReports()
	{
		$result = SproutReports_ReportRecord::model()->findAllByAttributes(array('enabled' => 1));

		if ($result)
		{
			return SproutReports_ReportModel::populateModels($result);
		}
	}

	/**
	 * @param int $groupId
	 *
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getReportsByGroupId($groupId)
	{
		$result = craft()->db->createCommand()
			->select('*')
			->from('sproutreports_reports')
			->where('groupId = :groupId', array(
				':groupId' => $groupId
			))
			->queryAll();

		if ($result)
		{
			return SproutReports_ReportModel::populateModels($result);
		}
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
		return (int)SproutReports_ReportRecord::model()->countByAttributes(array('dataSourceId' => $dataSourceId));
	}

	/**
	 * Returns a report model populated from saved/POSTed data
	 *
	 * @throws Exception
	 * @return SproutReports_ReportModel
	 */
	public function prepareFromPost()
	{
		$reportId = craft()->request->getPost('id');

		if ($reportId && is_numeric($reportId))
		{
			$instance = sproutReports()->reports->getReport($reportId);

			if (!$instance)
			{
				$instance->addError('id', Craft::t('Could not find a report with id {reportId}', compact('reportId')));
			}
		}
		else
		{
			$instance = new SproutReports_ReportModel();
		}

		$options = craft()->request->getPost('options');

		$instance->name         = craft()->request->getPost('name');
		$instance->handle       = craft()->request->getPost('handle');
		$instance->description  = craft()->request->getPost('description');
		$instance->options      = is_array($options) ? $options : array();
		$instance->dataSourceId = craft()->request->getPost('dataSourceId');
		$instance->enabled      = craft()->request->getPost('enabled');
		$instance->groupId      = craft()->request->getPost('groupId', null);

		return $instance;
	}

	/**
	 * @param SproutReports_ReportModel $report
	 * @return bool
	 */
	protected function validateOptions(SproutReports_ReportModel &$report)
	{
		$errors = array();

		$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);

		if (!$dataSource->validateOptions($report->options, $errors))
		{
			$report->addError('options', $errors);

			return false;
		}

		return true;
	}
}

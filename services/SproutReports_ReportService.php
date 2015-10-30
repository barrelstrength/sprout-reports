<?php
namespace Craft;

/**
 * Class SproutReports_ReportService
 *
 * @package Craft
 */
class SproutReports_ReportService extends BaseApplicationComponent
{
	/**
	 * @param SproutReports_ReportModel $model
	 *
	 * @return bool
	 */
	public function save(SproutReports_ReportModel &$model)
	{
		$old    = $model->id;
		$record = new SproutReports_ReportRecord();

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

		if (!$old)
		{
			$model->id = $record->id;
		}

		return true;
	}

	/**
	 * Registers one or more reports with our internal tracking system
	 *
	 * @param array|SproutReportsBaseReportModel $reports
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function register($reports)
	{
		if (!is_array($reports))
		{
			$reports = array($reports);
		}

		foreach ($reports as $report)
		{
			if ($report instanceof SproutReportsBaseReportModel)
			{
				$record = new SproutReports_ReportRecord();

				$record->name         = $report->getName();
				$record->handle       = $report->getHandle();
				$record->description  = $report->getDescription();
				$record->options      = $report->getOptions();
				$record->dataSourceId = $report->getDataSource()->getId();
				$record->enabled      = true;
				$record->groupId      = sproutReports()->groups->getOrCreateByName($report->getGroupName())->id;

				if (!$record->save())
				{
					throw new Exception(print_r($record->getErrors(), true));
				}
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @return SproutReports_ReportModel
	 */
	public function get($id)
	{
		$result = SproutReports_ReportRecord::model()->findById($id);

		if ($result)
		{
			return SproutReports_ReportModel::populateModel($result);
		}
	}

	/**
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getAll()
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
	public function getEnabled()
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
	 * @return null|SproutReports_ReportGroupModel[]
	 */
	public function getAllByGroupId($groupId)
	{
		$reports = SproutReports_ReportGroupRecord::model()->findAllByAttributes(compact('groupId'));

		if ($reports)
		{
			return SproutReports_ReportGroupModel::populateModels($reports);
		}
	}

	/**
	 * Returns a report model populated from saved/POSTed data
	 *
	 * @throws Exception
	 * @return SproutReports_ReportModel
	 */
	public function prepareFromPost()
	{
		$id = craft()->request->getPost('id');

		if ($id && is_numeric($id))
		{
			$instance = sproutReports()->reports->get($id);

			if (!$instance)
			{
				$instance->addError('id', Craft::t('Could not find a report with id {id}', compact('id')));
			}
		}
		else
		{
			$instance = new SproutReports_ReportModel();
		}

		$instance->name         = craft()->request->getPost('name');
		$instance->handle       = sproutReports()->createHandle($instance->name);
		$instance->description  = craft()->request->getPost('description');
		$instance->settings     = craft()->request->getPost('settings');
		$instance->options      = craft()->request->getPost('options');
		$instance->dataSourceId = craft()->request->getPost('dataSourceId');
		$instance->enabled      = craft()->request->getPost('enabled');
		$instance->groupId      = craft()->request->getPost('groupId', 1);

		return $instance;
	}
}

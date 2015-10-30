<?php
namespace Craft;

/**
 * Class SproutReports_ReportGroupService
 *
 * @package Craft
 */
class SproutReports_ReportGroupService extends BaseApplicationComponent
{
	/**
	 * @param SproutReports_ReportGroupModel &$model
	 *
	 * @return bool
	 */
	public function save(SproutReports_ReportGroupModel &$model)
	{
		$isNew  = !$model->id;
		$record = new SproutReports_ReportGroupRecord();

		$record->setAttributes($model->getAttributes(), false);

		if (!$record->validate())
		{
			$model->addErrors($record->getErrors());

			return false;
		}

		if (!$record->save())
		{
			$model->addError('general', Craft::t('Unable to save report group.'));

			return false;
		}

		if ($isNew)
		{
			$model->id = $record->id;
		}

		return true;
	}

	/**
	 * @param int $id
	 *
	 * @throws Exception
	 * @return SproutReports_ReportGroupModel
	 */
	public function get($id)
	{
		$group = SproutReports_ReportGroupRecord::model()->findByAttributes(compact('id'));

		if (!$group)
		{
			throw new Exception(Craft::t('Cannot find group with id {id}.', compact('id')));
		}

		return SproutReports_ReportGroupModel::populateModel($group);
	}

	/**
	 * @param string $handle
	 *
	 * @throws Exception
	 * @return SproutReports_ReportGroupModel
	 */
	public function getByHandle($handle)
	{
		$group = SproutReports_ReportGroupRecord::model()->findByAttributes(compact('handle'));

		if (!$group)
		{
			throw new Exception(Craft::t('Cannot find group with handle {handle}.', compact('handle')));
		}

		return SproutReports_ReportGroupModel::populateModel($group->getAttributes());
	}

	/**
	 * @return null|SproutReports_ReportGroupModel[]
	 */
	public function getAll()
	{
		$groups = SproutReports_ReportGroupRecord::model()->findAll();

		if ($groups)
		{
			return SproutReports_ReportGroupModel::populateModels($groups);
		}
	}

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function delete($id)
	{
		return (bool) SproutReports_ReportGroupRecord::model()->deleteByPk($id);
	}

	/**
	 * @param string $name
	 *
	 * @throws Exception
	 * @return SproutReports_ReportGroupModel
	 */
	public function getOrCreateByName($name)
	{
		$handle = sproutReports()->createHandle($name);

		try
		{
			return $this->getByHandle($handle);
		}
		catch (\Exception $e)
		{
			$group = new SproutReports_ReportGroupModel(compact('name', 'handle'));

			if ($this->save($group))
			{
				return $group;
			}

			throw new Exception(print_r($group->getErrors(), true));
		}
	}
}

<?php
namespace Craft;

/**
 * Class SproutReports_ReportGroupsService
 *
 * @package Craft
 */
class SproutReports_ReportGroupsService extends BaseApplicationComponent
{
	/**
	 * @param SproutReports_ReportGroupModel &$model
	 *
	 * @return bool
	 */
	public function saveGroup(SproutReports_ReportGroupModel &$group)
	{
		$groupRecord = $this->_getGroupRecord($group);
		$groupRecord->name = $group->name;

		if ($groupRecord->validate())
		{
			$groupRecord->save(false);

			// Now that we have an ID, save it on the model & models
			if (!$group->id)
			{
				$group->id = $groupRecord->id;
			}

			return true;
		}
		else
		{
			$group->addErrors($groupRecord->getErrors());
			return false;
		}
	}

	public function createGroupByName($name)
	{
		$group = new SproutReports_ReportGroupModel();
		$group->name = $name;

		if ($this->saveGroup($group))
		{
			return $group;
		}

		return false;
	}

	/**
	 * @param int $id
	 *
	 * @throws Exception
	 * @return SproutReports_ReportGroupModel
	 */
	public function getGroupById($id)
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
	public function getGroupByHandle($handle)
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
	public function getAllReportGroups()
	{
		$groups = SproutReports_ReportGroupRecord::model()->findAll(array('index'=>'id'));

		if ($groups)
		{
			return SproutReports_ReportGroupModel::populateModels($groups, 'id');
		}
	}

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function deleteGroup($id)
	{
		$record =  SproutReports_ReportGroupRecord::model()->findById($id);

		return (bool) $record->delete();
	}

	private function _getGroupRecord(SproutReports_ReportGroupModel $group)
	{
		if ($group->id)
		{
			$groupRecord = SproutReports_ReportGroupRecord::model()->findById($group->id);

			if (!$groupRecord)
			{
				throw new Exception(Craft::t('No field group exists with the ID “{id}”', array('id' => $group->id)));
			}
		}
		else
		{
			$groupRecord = new SproutReports_ReportGroupRecord();
		}

		return $groupRecord;
	}
}

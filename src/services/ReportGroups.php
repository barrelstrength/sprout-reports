<?php
namespace barrelstrength\sproutreports\services;

use yii\base\Component;
use barrelstrength\sproutreports\records\ReportGroup as ReportGroupRecord;
use barrelstrength\sproutreports\models\ReportGroup as ReportGroupModel;
use barrelstrength\sproutreports\SproutReports;

/**
 * Class ReportGroups
 *
 * @package barrelstrength\sproutreports\services
 */
class ReportGroups extends Component
{

	/**
	 * @param ReportGroupModel $group
	 *
	 * @return bool
	 */
	public function saveGroup(ReportGroupModel &$group)
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
		$group = new ReportGroupModel();
		$group->name = $name;

		if ($this->saveGroup($group))
		{
			return $group;
		}

		return false;
	}


	/**
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public function getAllReportGroups()
	{
		$groups = ReportGroupRecord::find()->indexBy('id')->all();

		return $groups;
	}

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function deleteGroup($id)
	{
		$record =  ReportGroupRecord::findOne($id);

		return (bool) $record->delete();
	}

	private function _getGroupRecord(ReportGroupModel $group)
	{
		if ($group->id)
		{
			$groupRecord = ReportGroupRecord::findOne($group->id);

			if (!$groupRecord)
			{
				throw new \Exception(SproutReports::t('No field group exists with the ID “{id}”', array('id' => $group->id)));
			}
		}
		else
		{
			$groupRecord = new ReportGroupRecord();
		}

		return $groupRecord;
	}
}

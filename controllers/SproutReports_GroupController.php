<?php
namespace Craft;

class SproutReports_GroupController extends BaseController
{
	/**
	 * Saves a group.
	 */
	public function actionSaveGroup()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$groupName = craft()->request->getRequiredPost('name');

		$group = new SproutReports_ReportGroupModel();
		$group->id = craft()->request->getPost('id');
		$group->name = $groupName;

		$isNewGroup = empty($group->id);

		if (sproutReports()->reportGroups->saveGroup($group))
		{
			if ($isNewGroup)
			{
				craft()->userSession->setNotice(Craft::t('Group added.'));
			}

			$this->returnJson(array(
				'success' => true,
				'group'   => $group->getAttributes(),
			));
		}
		else
		{
			$this->returnJson(array(
				'errors' => $group->getErrors(),
			));
		}
	}

	/**
	 * Deletes a group.
	 */
	public function actionDeleteGroup()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$groupId = craft()->request->getRequiredPost('id');
		$success = sproutReports()->reportGroups->deleteGroup($groupId);

		craft()->userSession->setNotice(Craft::t('Group deleted.'));

		$this->returnJson(array(
			'success' => $success,
		));
	}
}

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

			$group = new SproutReports_ReportGroupModel();
			$group->id = craft()->request->getPost('id');
			$group->name = craft()->request->getRequiredPost('name');

			$isNewGroup = empty($group->id);

			if (craft()->sproutReports_reports->saveGroup($group))
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
			$success = craft()->sproutReports_reports->deleteGroupById($groupId);

			craft()->userSession->setNotice(Craft::t('Group deleted.'));

			$this->returnJson(array(
				'success' => $success,
			));
		}
}
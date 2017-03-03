<?php

namespace barrelstrength\sproutreports\controllers;

use Craft;
use craft\web\Controller;
use barrelstrength\sproutreports\models\ReportGroup;
use barrelstrength\sproutreports\SproutReports;

class ReportGroupController extends Controller
{

	public function actionSaveGroup()
	{
		$this->requirePostRequest();

		$request = Craft::$app->getRequest();

		$groupName = $request->getBodyParam('name');

		$group       = new ReportGroup();
		$group->id   = $request->getBodyParam('id');
		$group->name = $groupName;

		if (SproutReports::$api->reportGroups->saveGroup($group))
		{
			Craft::$app->getSession()->setNotice(SproutReports::t('Report group saved.'));

			return $this->asJson(array(
				'success' => true,
				'group'   => $group->getAttributes(),
			));
		}
		else
		{
			return $this->asJson(array(
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

		$groupId = Craft::$app->getRequest()->getBodyParam('id');
		$success = SproutReports::$api->reportGroups->deleteGroup($groupId);

		Craft::$app->getSession()->setNotice(SproutReports::t('Group deleted..'));

		return $this->asJson(array(
			'success' => $success,
		));
	}
}
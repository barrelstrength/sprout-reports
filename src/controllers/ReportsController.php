<?php
namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutcore\SproutCore;
use Craft;
use craft\web\assets\cp\CpAsset;
use craft\web\Controller;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutcore\models\sproutreports\Report;
use barrelstrength\sproutcore\records\sproutreports\Report as ReportRecord;

class ReportsController extends Controller
{
	/**
	 * @param null $groupId
	 *
	 * @return \yii\web\Response
	 */
	public function actionIndex($groupId = null)
	{
		return $this->renderTemplate('sprout-reports/reports/index', [
			'groupId' => $groupId
		]);
	}
}

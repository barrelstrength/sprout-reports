<?php

namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutbase\SproutBase;
use Craft;
use craft\web\assets\cp\CpAsset;
use craft\web\Controller;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutbase\models\sproutreports\Report;
use barrelstrength\sproutbase\records\sproutreports\Report as ReportRecord;

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

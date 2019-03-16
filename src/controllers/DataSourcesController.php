<?php

namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutbasereports\models\DataSource as DataSourceModel;
use barrelstrength\sproutbasereports\SproutBaseReports;
use Craft;
use craft\web\Controller;
use yii\web\Response;

class DataSourcesController extends Controller
{
    /**
     * Save the Data Source
     *
     * @return Response
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveDataSource(): Response
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        $dataSourceId = $request->getBodyParam('dataSourceId');
        $allowNew = $request->getBodyParam('allowNew');

        $allowNew = empty($allowNew) ? false : true;

        $dataSource = new DataSourceModel();
        $dataSource->id = $dataSourceId;
        $dataSource->allowNew = $allowNew;

        if (SproutBaseReports::$app->dataSources->saveDataSource($dataSource)) {
            return $this->asJson(true);
        }

        return $this->asJson(false);
    }
}
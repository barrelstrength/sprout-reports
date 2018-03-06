<?php

namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutbase\models\sproutreports\DataSource as DataSourceModel;
use barrelstrength\sproutbase\SproutBase;
use Craft;
use craft\web\Controller;

class DataSourcesController extends Controller
{
    /**
     * Save the Data Source
     *
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveDataSource()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        $dataSourceId = $request->getBodyParam('dataSourceId');
        $allowNew = $request->getBodyParam('allowNew');

        $allowNew = empty($allowNew) ? false : true;

        $dataSource = new DataSourceModel();
        $dataSource->id = $dataSourceId;
        $dataSource->allowNew = $allowNew;

        if (SproutBase::$app->dataSources->saveDataSource($dataSource)) {
            return $this->asJson(true);
        }

        return $this->asJson(false);
    }
}
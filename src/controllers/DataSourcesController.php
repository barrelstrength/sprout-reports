<?php

namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutreports\models\DataSource as DataSourceModel;
use barrelstrength\sproutreports\SproutReports;
use Craft;
use craft\web\Controller;

class DataSourcesController extends Controller
{
	/**
	 * Save the Data Source
	 */
	public function actionUpdateDataSource()
	{
		$this->requirePostRequest();

		$request = Craft::$app->getRequest();

		$allowNew     = $request->getBodyParam('allowNew');
		$dataSourceId = $request->getBodyParam('dataSourceId');

		$allowNew = (empty($allowNew)) ? false : true;

		$attributes = array(
			'allowNew'     => $allowNew,
			'dataSourceId' => $dataSourceId
		);

		$model = new DataSourceModel;

		$model->setAttributes($attributes);

		if (SproutReports::$api->dataSources->saveDataSource($model))
		{
			return $this->asJson(true);
		}

		return $this->asJson(false);
	}
}
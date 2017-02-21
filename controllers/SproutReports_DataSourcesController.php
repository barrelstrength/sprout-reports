<?php
namespace Craft;

class SproutReports_DataSourcesController extends BaseController
{
	/**
	 * Save the Data Source
	 */
	public function actionUpdateDataSource()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$allowNew     = craft()->request->getRequiredPost('allowNew');
		$dataSourceId = craft()->request->getRequiredPost('dataSourceId');

		$allowNew = (empty($allowNew)) ? false : true;

		$attributes = array(
			'allowNew'     => $allowNew,
			'dataSourceId' => $dataSourceId
		);

		$model = SproutReports_DataSourceModel::populateModel($attributes);

		if (sproutReports()->dataSources->saveDataSource($model))
		{
			$this->returnJson(true);
		}

		$this->returnJson(false);
	}
}

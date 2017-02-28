<?php
namespace barrelstrength\sproutreports\models;

use craft\base\Model;
use barrelstrength\sproutreports\SproutReports;

class Report extends Model
{
	public $id;

	public $name;

	public $handle;

	public $description;

	public $allowHtml;

	public $options;

	public $dataSourceId;

	public $enabled;

	public $groupId;

	public function getDataSource()
	{
		$dataSource = SproutReports::$api->dataSources->getDataSourceById($this->dataSourceId);

		$dataSource->setReport($this);

		return $dataSource;
	}

	public function getOptions()
	{
		return $this->options;
	}
}
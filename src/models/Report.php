<?php
namespace barrelstrength\sproutreports\models;

use craft\base\Model;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutreports\records\Report as ReportRecord;
use craft\validators\HandleValidator;
use craft\validators\UniqueValidator;

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

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'handle'], 'required'],
			[['handle'], HandleValidator::class, 'reservedWords' => ['id', 'dateCreated', 'dateUpdated', 'uid', 'title']],
			[['name', 'handle'], UniqueValidator::class, 'targetClass' => ReportRecord::class]
		];
	}
}
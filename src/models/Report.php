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

	public $dateCreated;

	public $dateUpdated;

	public function getDataSourceId()
	{
		return $this->dataSourceId;
	}

	public function getDataSource()
	{
		$dataSource = SproutReports::$app->dataSources->getDataSourceById($this->dataSourceId);

		$dataSource->setReport($this);

		return $dataSource;
	}

	public function getOptions()
	{
		$options = $this->options;

		if (is_string($this->options))
		{
			$options = json_decode($this->options);
		}

		return $options;
	}

	/**
	 * Returns a user supplied option if it exists or $default otherwise
	 *
	 * @param string     $name
	 * @param null|mixed $default
	 *
	 * @return null
	 */
	public function getOption($name, $default = null)
	{
		$options = $this->getOptions();

		if (is_string($name) && !empty($name) && isset($options->$name))
		{
			return $options->$name;
		}

		return $default;
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

	public function safeAttributes()
	{
		return [
			'id', 'name', 'handle',
			'description', 'allowHtml', 'options',
			'dataSourceId', 'enabled', 'groupId',
		  'dateCreated', 'dateUpdated'
		];
	}

	/**
	 * @return string
	 */
	public function getEditUrl()
	{
		return $this->getDataSource()->getUrl('edit/'.$this->id);
	}

	/**
	 * @param array $results
	 */
	public function setResults(array $results = array())
	{
		$this->results = $results;
	}

	/**
	 * @param string $message
	 */
	public function setResultsError($message)
	{
		$this->addError('results', $message);
	}
}
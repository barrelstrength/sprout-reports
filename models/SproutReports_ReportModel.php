<?php
namespace Craft;

/**
 * Class SproutReportsModel
 *
 * @package Craft
 * --
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property string $description
 * @property array  $options
 * @property string $dataSourceId
 * @property int    $groupId
 * @property int    $enabled
 */
class SproutReports_ReportModel extends BaseModel
{
	/**
	 * @var array
	 */
	protected $results;

	/**
	 * Returns an array of user supplied options
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
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
		if (is_string($name) && !empty($name) && isset($this->options[$name]))
		{
			return $this->options[$name];
		}

		return $default;
	}

	public function getDataSourceId()
	{
		return $this->dataSourceId;
	}

	public function getDataSource()
	{
		$dataSource = sproutReports()->dataSources->getDataSourceById($this->getDataSourceId());

		// @todo - consider alternative ways to handle this
		// Do we need to add a complete Model here? It's currently used when handling errors
		// in a Reports getOptionsHtml() method, as the errors are assigned to the Report
		// but the validation of those errors is managed by the Data Source
		$dataSource->setReport($this);

		return $dataSource;
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

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'id'           => array(AttributeType::Number),
			'name'         => array(AttributeType::String, 'required' => true),
			'handle'       => array(AttributeType::Handle, 'required' => true),
			'description'  => array(AttributeType::String, 'default' => null),
			'options'      => array(AttributeType::Mixed, 'default' => array()),
			'dataSourceId' => array(AttributeType::String, 'required' => true),
			'enabled'      => array(AttributeType::Bool, 'default' => true),
			#
			# @related
			'groupId'      => array(AttributeType::Number, 'required' => true)
		);
	}
}

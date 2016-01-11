<?php
namespace Craft;

/**
 * Class SproutReports_ReportRecord
 *
 * @package Craft
 * --
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property string $description
 * @property array $options
 * @property string $dataSourceId
 * @property int $groupId
 * @property int $enabled
 */
class SproutReports_ReportRecord extends BaseRecord
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'sproutreports_reports';
	}

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'groupId'      => array(AttributeType::Number),
			'name'         => array(AttributeType::String, 'required' => true),
			'handle'       => array(AttributeType::Handle, 'required' => true),
			'description'  => array(AttributeType::String, 'default' => null),
			'dataSourceId' => array(AttributeType::String, 'required' => true),
			'options'      => array(AttributeType::Mixed, 'required' => false),
			'enabled'      => array(AttributeType::Bool, 'default' => true)
		);
	}

	/**
	 * @return array
	 */
	public function defineIndexes()
	{
		return array(
			array('columns' => array('name', 'handle'), 'unique' => true),
			array('columns' => array('dataSourceId')),
		);
	}

	/**
	 * @return array
	 */
	public function scopes()
	{
		return array(
			'ordered' => array('order' => 'dataSourceId, name'),
		);
	}
}
<?php
namespace Craft;

/**
 * Class SproutReports_DataSourceRecord
 *
 * @package Craft
 * --
 * @property string $dataSourceId
 * @property array  $options
 * @property int    $allowNew
 */
class SproutReports_DataSourceRecord extends BaseRecord
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'sproutreports_datasources';
	}

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'dataSourceId' => array(AttributeType::String, 'required' => true),
			'options'      => array(AttributeType::Mixed, 'required' => false),
			'allowNew'     => array(AttributeType::Bool, 'default' => true)
		);
	}
}
<?php
namespace Craft;

class SproutReports_ReportRecord extends BaseRecord
{
	public function getTableName()
	{
		return 'sproutreports_reports';
	}

	protected function defineAttributes()
	{
		return array(
			'groupId' => array(AttributeType::Number),
			'name' => array(AttributeType::String, 'required' => true),
			'handle' => array(AttributeType::String, 'required' => true),
			'description'	=> array(AttributeType::String),
			'customQuery'	=> array(AttributeType::Mixed),
		);
	}

	public function defineIndexes()
	{
		return array(
			array('columns' => array('name', 'handle'), 'unique' => true),
		);
	}

	public function create()
	{
		$class	= get_class($this);
		$record	= new $class();

		return $record;
	}

}

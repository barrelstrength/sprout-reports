<?php
namespace Craft;

/**
 * Class SproutReports_ReportGroupRecord
 *
 * @package Craft
 * --
 * @property int    $id
 * @property string $name
 * @property string $handle
 */
class SproutReports_ReportGroupRecord extends BaseRecord
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'sproutreports_reportgroups';
	}

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'name' => array(AttributeType::Name, 'required' => true)
		);
	}

	/**
	 * @return array
	 */
	public function defineRelations()
	{
		return array(
			'reports' => array(static::HAS_MANY, 'SproutReports_ReportRecord', 'groupId'),
		);
	}

	/**
	 * @return array
	 */
	public function defineIndexes()
	{
		return array(
			array('columns' => array('name'), 'unique' => true)
		);
	}

	/**
	 * @return array
	 */
	public function scopes()
	{
		return array(
			'ordered' => array('order' => 'name'),
		);
	}
}

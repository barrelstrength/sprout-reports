<?php
namespace Craft;

/**
 * Class SproutReports_ReportGroupModel
 *
 * @package Craft
 * --
 * @property int    $id
 * @property string $name
 * @property string $handle
 */
class SproutReports_ReportGroupModel extends BaseModel
{
	/**
	 * @return string
	 */
	public function __toString()
	{
		return Craft::t($this->name);
	}

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'id'     => array(AttributeType::Number),
			'name'   => array(AttributeType::String, 'required' => true)
		);
	}

	/**
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getReports()
	{
		return sproutReports()->getReportsByGroupId($this->id);
	}
}

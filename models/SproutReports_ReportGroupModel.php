<?php
namespace Craft;

/**
 * Section model class
 *
 * Used for transporting section data throughout the system.
 */
class SproutReports_ReportGroupModel extends BaseModel
{
	private $_locales;
	private $_fieldLayout;

	/**
	 * Use the translated section name as the string representation.
	 *
	 * @return string
	 */
	function __toString()
	{
		return Craft::t($this->name);
	}

	/**
	 * @access protected
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'id'            => AttributeType::Number,
			'name'          => AttributeType::String,
		);
	}

	/**
	 * Returns the group's fields.
	 *
	 * @return array
	 */
	public function getReports()
	{
		return craft()->sproutReports->getFormsByGroupId($this->id);
	}

}

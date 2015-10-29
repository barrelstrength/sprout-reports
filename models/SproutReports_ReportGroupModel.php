<?php
namespace Craft;

class SproutReports_ReportGroupModel extends BaseModel
{
	function __toString()
	{
		return Craft::t($this->name);
	}

	protected function defineAttributes()
	{
		return array(
			'id'   => AttributeType::Number,
			'name' => AttributeType::String,
		);
	}

	public function getReports()
	{
		return sproutReports()->getReportsByGroupId($this->id);
	}
}

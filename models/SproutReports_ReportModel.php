<?php
namespace Craft;

class SproutReports_ReportModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'id'			=> array(AttributeType::Number),
			'name'			=> array(AttributeType::String),
			'handle'		=> array(AttributeType::String),
			'description'	=> array(AttributeType::String),
			'customQuery'	=> array(AttributeType::Mixed),
		);
	}
}

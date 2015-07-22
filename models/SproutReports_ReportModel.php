<?php
namespace Craft;

class SproutReports_ReportModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'id'					=> array(AttributeType::Number),
			'groupId'				=> array(AttributeType::Number),
			'name'					=> array(AttributeType::String, 'required' => true),
			'handle'				=> array(AttributeType::String, 'required' => true),
			'description'			=> AttributeType::String,
			'customQuery'			=> AttributeType::Mixed,
      'settings'              => AttributeType::Mixed,
			'returnsSingleNumber'	=> array(AttributeType::Bool, 'default' => false),
			'isEmailList'	=> array(AttributeType::Bool, 'default' => false)
		);
	}
}

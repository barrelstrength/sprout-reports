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
			'returnsSingleNumber'	=> array(AttributeType::Bool, 'default' => false, 'required' => true),
			'isEmailList'	=> array(AttributeType::Bool, 'default' => false, 'required' => true),
            'settings'              => AttributeType::Mixed,
			'customQueryEditable'	=> array(AttributeType::Bool, 'default' => true),
			'queryParamsHandler'	=> array(AttributeType::Mixed, 'default' => true),
		);
	}
}

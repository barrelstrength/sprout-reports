<?php
namespace Craft;

class SproutReports_SettingsModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'pluginNameOverride'     => AttributeType::String
		);
	}
}
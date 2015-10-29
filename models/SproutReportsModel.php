<?php
namespace Craft;

/**
 * Class SproutReportsModel
 *
 * @package Craft
 * --
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property string $description
 * @property array  $settings
 * @property array  $options
 * @property string $dataSetId
 * @property int    $groupId
 * @property int    $enabled
 */
class SproutReportsModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'id'          => Attributetype::Number,
			'name'        => AttributeType::String,
			'handle'      => AttributeType::Handle,
			'description' => AttributeType::Handle,
			'settings'    => AttributeType::Mixed,  // Native to Sprout Reports
			'options'     => AttributeType::Mixed,  // End User provided
			'dataSetId'   => AttributeType::String,
			'enabled'     => AttributeType::Bool,
			#
			# @related
			'groupId'     => AttributeType::Number,
		);
	}
}

<?php
namespace Craft;

/**
 * Class SproutReportsRecord
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
class SproutReportsRecord extends BaseRecord
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'sproutreports';
	}

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'name'        => AttributeType::String, // Pending Users Report
			'handle'      => AttributeType::Handle, // pending-users
			'description' => AttributeType::String, // Returns all the users that have not activated their account
			'settings'    => AttributeType::Mixed,  // {"isSingleNumber": "1", "isEmailList": "1"}
			'options'     => AttributeType::Mixed,  // {"pendingUsers": "1", "groups": "members"}
			'dataSetId'   => AttributeType::String, // sproutreportsusers
		    'enabled'     => AttributeType::Bool,
			#
			# @ related
			'groupId'     => AttributeType::Number,
		);
	}

	public function defineIndexes()
	{
		return array(
			array('columns' => array('handle'), 'unique' => true),
			array('columns' => array('dataSetId')),
		);
	}
}

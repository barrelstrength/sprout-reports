<?php
namespace barrelstrength\sproutfields\records;

use craft\db\ActiveRecord;
use yii\db\ActiveQueryInterface;

/**
 * Class SproutReports_ReportRecord
 *
 * @package Craft
 * --
 * @property int $id               ID
 * @property string $name          Name
 * @property string $handle        Handle
 * @property string $description   Description
 * @property array $options        Options
 * @property string $dataSourceId  Data Source ID
 * @property int $groupId          Group ID
 * @property int $enabled          Enabled
 */
class Report extends ActiveRecord
{
	/**
	 * @return string
	 */
	public static function tableName(): string
	{
		return '{{%sproutreports_reports}}';
	}

	//
	///**
	// * @return array
	// */
	//protected function defineAttributes()
	//{
	//	return array(
	//		'groupId'      => array(AttributeType::Number),
	//		'name'         => array(AttributeType::String, 'required' => true),
	//		'handle'       => array(AttributeType::Handle, 'required' => true),
	//		'description'  => array(AttributeType::String, 'default' => null),
	//		'allowHtml'   => array(AttributeType::Bool, 'required' => false),
	//		'dataSourceId' => array(AttributeType::String, 'required' => true),
	//		'options'      => array(AttributeType::Mixed, 'required' => false),
	//		'enabled'      => array(AttributeType::Bool, 'default' => true)
	//	);
	//}
	//
	///**
	// * @return array
	// */
	//public function defineIndexes()
	//{
	//	return array(
	//		array('columns' => array('name', 'handle'), 'unique' => true),
	//		array('columns' => array('dataSourceId')),
	//	);
	//}
	//
	///**
	// * @return array
	// */
	//public function scopes()
	//{
	//	return array(
	//		'ordered' => array('order' => 'dataSourceId, name'),
	//	);
	//}
}
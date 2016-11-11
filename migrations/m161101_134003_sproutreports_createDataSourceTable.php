<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m161101_134003_sproutreports_createDataSourceTable extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{
		$tableName = 'sproutreports_datasources';

		SproutReportsPlugin::log('Creating the sproutreports_datasources table');

		// Create the sproutreports_datasources table
		craft()->db->createCommand()->createTable($tableName, array(
			'id'           => array('column' => ColumnType::PK, 'null' => false),
			'dataSourceId' => AttributeType::String,
			'allowNew'     => array('column' => 'integer'),
			'options'      => array('column' => 'text')
		), null, false);

		craft()->db->createCommand()->createIndex($tableName, 'id');

		SproutReportsPlugin::log('Finished creating the sproutreports_datasources table');

		return true;
	}
}

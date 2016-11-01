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
        SproutEmailPlugin::log('Creating the sproutreports_datasources table');

        $tableName = 'sproutreports_datasources';

        if (!craft()->db->tableExists($tableName))
        {
            try
            {
                // Create the sproutreports_datasources table
                craft()->db->createCommand()->createTable($tableName, array(
                    'id'            => array('column' => 'integer', 'required' => true),
                    'dataSourceId'  => AttributeType::String,
                    'status'        => array('column' => 'integer'),
                    'options'       => array('column' => 'text')
                ), null, false);

                craft()->db->createCommand()->addPrimaryKey($tableName, 'id');
            }
            catch (\Exception $e)
            {
                SproutEmailPlugin::log('Error creating the sproutreports_datasources table: ' . $e->getMessage());
            }
        }

        SproutEmailPlugin::log('Finished creating the sproutreports_datasources table');
        
		return true;
	}
}

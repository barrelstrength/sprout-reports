<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m140322_120001_sproutReports_addGroupsTable extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{		
		// ADD A TABLE TO THE DATABASE
		
		// The Table you wish to add. 'craft_' prefix will be added automatically.
		$tableName = 'sproutreports_reportgroups';
		
		if (!craft()->db->tableExists($tableName))
		{
			Craft::log("Creating the `$tableName` table.", LogLevel::Info, true);
		
			// Review Column Types in craft/app/enums/ColumnType.php
			$this->createTable($tableName, array(
				'name' => array( 'column' => ColumnType::Varchar, 'null' => false )
			));
		}

		$this->createIndex($tableName, 'name', true);
		
		return true;
	}
}
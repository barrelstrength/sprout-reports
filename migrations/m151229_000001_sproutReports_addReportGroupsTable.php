<?php
namespace Craft;

class m151229_000001_sproutReports_addReportGroupsTable extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		$tableName = 'sproutreports_reportgroups';

		if (!craft()->db->tableExists($tableName))
		{
			SproutReportsPlugin::log("Creating the {$tableName} table.");

			craft()->db->createCommand()->createTable($tableName, array(
				'id'   			   => array('column' => ColumnType::Int, 'null' => false),
				'name'         => array('column' => ColumnType::Varchar, 'null' => false),
				'dateCreated'  => array('column' => ColumnType::DateTime, 'null' => false),
				'dateUpdated'  => array('column' => ColumnType::DateTime, 'null' => false),
				'uid'      		 => array('column' => 'char(36)', 'null' => false, 'default'=>'0'),
				), null, true, false
			);

			craft()->db->createCommand()->addPrimaryKey($tableName, 'id');
			craft()->db->createCommand()->createIndex($tableName, 'id');

			SproutReportsPlugin::log("Finished creating the {$tableName} table.");
		}
		else
		{
			SproutReportsPlugin::log("The {$tableName} table already exists", LogLevel::Info, true);
		}

		return true;
	}
}

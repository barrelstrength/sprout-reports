<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m140322_120000_sproutReports_addGroups extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{
				// ADD A COLUMN TO A TABLE IN THE DATABASE
		
				$reportsTable = $this->dbConnection->schema->getTable('{{sproutreports_reports}}');
		
				if ($reportsTable)
				{
					if (($column = $reportsTable->getColumn('groupId')) == null)
					{
						Craft::log('Adding `groupId` column to the `sproutreports_reports` table.', LogLevel::Info, true);
		
						$this->addColumnAfter('sproutreports_reports', 'groupId', array(AttributeType::String, 'required' => false), 'id');
		
						Craft::log('Added `groupId` column to the `sproutreports_reports` table.', LogLevel::Info, true);
					}
					else
					{
						Craft::log('Tried to add a `groupId` column to the `sproutreports_reports` table, but there is already one there.', LogLevel::Warning);
					}
				}
				else
				{
					Craft::log('Could not find an `sproutreports_reports` table. Wut?', LogLevel::Error);
				}
		

		return true;
	}
}
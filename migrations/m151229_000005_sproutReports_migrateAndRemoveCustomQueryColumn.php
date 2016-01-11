<?php
namespace Craft;

class m151229_000005_sproutReports_migrateAndRemoveCustomQueryColumn extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		if (($table = $this->dbConnection->schema->getTable('{{sproutreports_reports}}')))
		{
			if ($table->getColumn('customQuery') != null)
			{
				// Migrate Custom Query Reports
				SproutReportsPlugin::log('Migrating custom queries.', LogLevel::Info, true);

				$tablePrefix = craft()->db->tablePrefix;
				$tableName = $tablePrefix . 'sproutreports_reports';

				$sql = "UPDATE $tableName AS reports
								SET dataSourceId = 'sproutreports.query',
										options = CONCAT('{\"query\":\"', reports.customQuery, '\"}'),
										enabled = 1,
										dateUpdated = NOW()
								WHERE reports.customQuery != ''";

				craft()->db->createCommand($sql)->execute();

				SproutReportsPlugin::log('Custom queries migrated.', LogLevel::Info, true);

				
				// Remove Old Custom Query Column
				SproutReportsPlugin::log('Dropping `customQuery` column', LogLevel::Info);

				$this->dropColumn('{{sproutreports_reports}}', 'customQuery');

				SproutReportsPlugin::log('Dropped `customQuery` column', LogLevel::Info);

			}
			else
			{
				SproutReportsPlugin::log('Could not find the `customQuery` column.', LogLevel::Info, true);
			}
		}
		else
		{
			SproutReportsPlugin::log('Could not find the `sproutreports_reports` table.', LogLevel::Info, true);
		}

		return true;
	}
}
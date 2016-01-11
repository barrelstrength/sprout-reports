<?php
namespace Craft;

class m151229_000006_sproutReports_addDetailsToReportsTable extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		if (($table = $this->dbConnection->schema->getTable('{{sproutreports_reports}}')))
		{
			SproutReportsPlugin::log('Creating `dataSourceId` index.', LogLevel::Info);

			craft()->db->createCommand()->createIndex('sproutreports_reports', 'dataSourceId');

			SproutReportsPlugin::log('Dropping groupId Foreign Key.', LogLevel::Info, true);

			MigrationHelper::dropForeignKeyIfExists('sproutreports_reports', 'groupId');

			SproutReportsPlugin::log('Creating groupId Foreign Key.', LogLevel::Info, true);

			// Make reports 'groupId' a FK to report groups 'id'
			craft()->db->createCommand()->addForeignKey('sproutreports_reports', 'groupId', 'sproutreports_reportgroups',
				'id', 'CASCADE');
		}
		else
		{
			SproutReportsPlugin::log('Could not find the `sproutreports_reports` table.', LogLevel::Info, true);
		}

		return true;
	}
}
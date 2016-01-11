<?php
namespace Craft;

class m151229_000007_sproutReports_updatePermissionNames extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		if (($table = $this->dbConnection->schema->getTable('{{sproutreports_reports}}')))
		{
			SproutReportsPlugin::log("Updating 'editreports' permission.", LogLevel::Info);

			craft()->db->createCommand()->update(
				'userpermissions',
				array('name' => 'editreports'),
				'name = :name',
				array(':name' => 'sproutreports-editreports')
			);

			SproutReportsPlugin::log("Updating 'editsproutreportsreports' permission.", LogLevel::Info);

			craft()->db->createCommand()->update(
				'userpermissions',
				array('name' => 'editsproutreportsreports'),
				'name = :name',
				array(':name' => 'sproutreports-editreports')
			);

		}
		else
		{
			SproutReportsPlugin::log('Could not find the `sproutreports_reports` table.', LogLevel::Info, true);
		}

		return true;
	}
}
<?php
namespace Craft;

class m151229_000006_sproutReports_addDetailsToReportsTable extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		SproutReportsPlugin::log('Dropping groupId Foreign Key.', LogLevel::Info, true);

		MigrationHelper::dropForeignKeyIfExists('sproutreports_reports', 'groupId');

		return true;
	}
}
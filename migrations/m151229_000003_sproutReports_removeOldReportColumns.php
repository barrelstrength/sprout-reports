<?php
namespace Craft;

class m151229_000003_sproutReports_removeOldReportColumns extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		if (($table = $this->dbConnection->schema->getTable('{{sproutreports_reports}}')))
		{
			$oldColumns = array(
				'returnsSingleNumber',
				'isEmailList',
				'settings',
				'customQueryEditable',
				'queryParamsHandler'
			);

			SproutReportsPlugin::log('Preparing to delete old columns', LogLevel::Info);

			foreach ($oldColumns as $oldColumn)
			{
				if (($column = $table->getColumn($oldColumn)))
				{
					$this->dropColumn('{{sproutreports_reports}}', $oldColumn);

					SproutReportsPlugin::log('Dropped column: ' . $oldColumn, LogLevel::Info);
				}
			}

			SproutReportsPlugin::log('Old columns deleted', LogLevel::Info);

		}
		else
		{
			SproutReportsPlugin::log('Could not find the `sproutreports_reports` table.', LogLevel::Error);
		}

		return true;
	}
}

<?php
namespace Craft;

class m140628_093000_sproutReports_addIsEmailListColumn extends BaseMigration
{
	public function safeUp()
	{
		$reportsTable = $this->dbConnection->schema->getTable('{{sproutreports_reports}}');

		if ($reportsTable)
		{
			if (is_null($reportsTable->getColumn('isEmailList')))
			{
				Craft::log('Adding `isEmailList` column to the `sproutreports_reports` table.', LogLevel::Info, true);

				$this->addColumnAfter('sproutreports_reports', 'isEmailList', array('column' => ColumnType::Bool, 'default' => false, 'required' => true), 'returnsSingleNumber');

				Craft::log('Added `isEmailList` column to the `sproutreports_reports` table.', LogLevel::Info, true);
			}
			else
			{
				Craft::log('Tried to add a `isEmailList` column to the `sproutreports_reports` table, but there is already one there.', LogLevel::Warning);
			}
		}
		else
		{
			Craft::log('Could not find an `sproutreports_reports` table.', LogLevel::Error);
		}

		return true;
	}
}
<?php
namespace Craft;

class m140324_093000_sproutReports_addReturnsSingleNumberColumn extends BaseMigration
{
	public function safeUp()
	{
		$reportsTable = $this->dbConnection->schema->getTable('{{sproutreports_reports}}');

		if ($reportsTable)
		{
			if (is_null($reportsTable->getColumn('returnsSingleNumber')))
			{
				Craft::log('Adding `returnsSingleNumber` column to the `sproutreports_reports` table.', LogLevel::Info, true);

				$this->addColumnAfter('sproutreports_reports', 'returnsSingleNumber', array('column' => ColumnType::Bool, 'default' => false, 'required' => true), 'customQuery');

				Craft::log('Added `returnsSingleNumber` column to the `sproutreports_reports` table.', LogLevel::Info, true);
			}
			else
			{
				Craft::log('Tried to add a `returnsSingleNumber` column to the `sproutreports_reports` table, but there is already one there.', LogLevel::Warning);
			}
		}
		else
		{
			Craft::log('Could not find an `sproutreports_reports` table.', LogLevel::Error);
		}

		return true;
	}
}
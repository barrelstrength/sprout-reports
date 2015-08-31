<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m150831_195322_sproutreports_queryParamsHandler extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{
        $reportsTable = $this->dbConnection->schema->getTable('{{sproutreports_reports}}');

        if ($reportsTable)
        {
            if (is_null($reportsTable->getColumn('queryParamsHandler')))
            {
                Craft::log('Adding `queryParamsHandler` column to the `sproutreports_reports` table.', LogLevel::Info, true);

                $this->addColumnAfter('sproutreports_reports', 'queryParamsHandler', array(ColumnType::Text, 'required' => false), 'customQueryEditable');

                Craft::log('Added `queryParamsHandler` column to the `sproutreports_reports` table.', LogLevel::Info, true);
            }
            else
            {
                Craft::log('Tried to add a `queryParamsHandler` column to the `sproutreports_reports` table, but there is already one there.', LogLevel::Warning);
            }
        }
        else
        {
            Craft::log('Could not find an `sproutreports_reports` table.', LogLevel::Error);
        }

        return true;
	}
}

<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m150825_173228_sproutreports_reports_custom_query_editable extends BaseMigration
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
            if (is_null($reportsTable->getColumn('customQueryEditable')))
            {
                Craft::log('Adding `customQueryEditable` column to the `sproutreports_reports` table.', LogLevel::Info, true);

                $this->addColumnAfter('sproutreports_reports', 'customQueryEditable', array(ColumnType::Bool, 'required' => true, 'default'=>true), 'returnsSingleNumber');

                Craft::log('Added `customQueryEditable` column to the `sproutreports_reports` table.', LogLevel::Info, true);
            }
            else
            {
                Craft::log('Tried to add a `customQueryEditable` column to the `sproutreports_reports` table, but there is already one there.', LogLevel::Warning);
            }
        }
        else
        {
            Craft::log('Could not find an `sproutreports_reports` table.', LogLevel::Error);
        }

        return true;
    }
}

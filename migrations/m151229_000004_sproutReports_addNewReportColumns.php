<?php
namespace Craft;

class m151229_000004_sproutReports_addNewReportColumns extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		if (($table = $this->dbConnection->schema->getTable('{{sproutreports_reports}}')))
		{
			if (($column = $table->getColumn('enabled')) == null)
			{
				$enabledColumn = array(
					AttributeType::Bool,
					'column' => ColumnType::TinyInt,
					'length' => 1,
					'default' => true,
					'required' => true,
					'unsigned' => true
				);

				$this->addColumnAfter('sproutreports_reports', 'enabled', $enabledColumn, 'description');
			}
			else
			{
				Craft::log('Tried to add a `enabled` column to the `sproutreports_reports` table, but it already exists.',
					LogLevel::Warning);
			}

			if (($column = $table->getColumn('options')) == null)
			{
				$optionsColumn = array(
					AttributeType::Mixed,
					'column' => ColumnType::Text,
					'required' => false
				);

				$this->addColumnAfter('sproutreports_reports', 'options', $optionsColumn, 'description');
			}
			else
			{
				Craft::log('Tried to add a `options` column to the `sproutreports_reports` table, but it already exists.',
					LogLevel::Warning);
			}

			if (($column = $table->getColumn('dataSourceId')) == null)
			{
				$dataSourceIdColumn = array(
					AttributeType::String,
					'column' => ColumnType::Varchar,
					'required' => true
				);

				$this->addColumnAfter('sproutreports_reports', 'dataSourceId', $dataSourceIdColumn, 'description');
			}
			else
			{
				Craft::log('Tried to add a `enabled` column to the `sproutreports_reports` table, but it already exists.',
					LogLevel::Warning);
			}
		}
		else
		{
			Craft::log('Could not find the `sproutreports_reports` table.', LogLevel::Error);
		}

		return true;
	}
}

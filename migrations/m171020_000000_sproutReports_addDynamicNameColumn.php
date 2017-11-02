<?php
namespace Craft;
/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m171020_000000_sproutReports_addNameFormatColumn extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		$tableName  = 'sproutreports_reports';
		$columnName = 'nameFormat';

		$this->addColumnAfter($tableName, $columnName,
			array(
				'column'   => ColumnType::Varchar,
				'required' => false,
				'default'  => null,
				'length' => 255,
			  'null' => true
			),
			'description'
		);

		SproutReportsPlugin::log("Created the column `$columnName` in `$tableName` .", LogLevel::Info, true);

		return true;
	}
}

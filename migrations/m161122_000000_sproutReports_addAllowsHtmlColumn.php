<?php
namespace Craft;
/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m161122_000000_sproutReports_addAllowsHtmlColumn extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		$tableName  = 'sproutreports_reports';
		$columnName = 'allowsHtml';


		$this->addColumnAfter($tableName, $columnName,
			array(
				'column'   => ColumnType::Bool,
				'required' => false,
				'default'  => false,
			),
			'description'
		);

		SproutReportsPlugin::log("Created the column `$columnName` in `$tableName` .", LogLevel::Info, true);

		return true;
	}
}

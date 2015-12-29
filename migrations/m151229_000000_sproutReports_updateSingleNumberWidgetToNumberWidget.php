<?php
namespace Craft;

class m151229_000000_sproutReports_updateSingleNumberWidgetToNumberWidget extends BaseMigration
{
	/**
	 * @return bool
	 */
	public function safeUp()
	{
		if (($table = $this->dbConnection->schema->getTable('{{widgets}}')))
		{
			if ($table->getColumn('type') != null)
			{
				$singleNumberWidgets = craft()->db->createCommand()
					->select('id, type')
					->from('widgets')
					->where('type=:type', array(':type'=>'SproutReports_SingleNumber'))
					->queryAll();

				if ($count = count($singleNumberWidgets))
				{
					SproutReportsPlugin::log('Single Number Widgets found: '. $count, LogLevel::Info, true);

					SproutReportsPlugin::log('Migrating Single Number Widgets', LogLevel::Info, true);

					craft()->db->createCommand()->update('widgets', array(
							'type' => 'SproutReports_NumberReport'
						), 'type= :type', array(':type' => 'SproutReports_SingleNumber')
					);

					SproutReportsPlugin::log('Migration of Single Number Widgets => Number Widgets complete', LogLevel::Info,
						true);
				}

				SproutReportsPlugin::log('No Single Number Widgets to migrate.', LogLevel::Info, true);
			}
			else
			{
				SproutReportsPlugin::log('Could not find the `type` column.', LogLevel::Info, true);
			}
		}
		else
		{
			SproutReportsPlugin::log('Could not find the `widgets` table.', LogLevel::Info, true);
		}

		return true;
	}
}
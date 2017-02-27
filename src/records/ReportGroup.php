<?php
namespace barrelstrength\sproutreports\records;

use craft\db\ActiveRecord;

/**
 * Class SproutReports_ReportGroupRecord
 *
 * @package Craft
 * --
 * @property int    $id
 * @property string $name
 * @property string $handle
 */
class ReportGroup extends ActiveRecord
{
	/**
	 * @return string
	 */
	public static function tableName(): string
	{
		return '{{%sproutreports_reportgroups}}';
	}

	public function getReports()
	{
		return $this->hasMany(Report::class, ['groupId' => 'id']);
	}

	protected function beforeDelete()
	{
/*
		$reports = SproutReports_ReportRecord::model()->findAll('groupId =:groupId',array(
				':groupId' => $this->id
			)
		);

		foreach ($reports as $report)
		{
			$record = SproutReports_ReportRecord::model()->findById($report->id);
			$record->groupId = null;
			$record->save(false);
		}*/

		return true;
	}
}

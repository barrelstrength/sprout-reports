<?php
namespace Craft;

class SproutReportsService extends BaseApplicationComponent
{
	/**
	 * @var SproutReports_ReportGroupService
	 */
	public $groups;

	/**
	 * @var SproutReports_DataSourceService
	 */
	public $sources;

	/**
	 * @var SproutReports_ExportService
	 */
	public $exports;

	/**
	 * @var SproutReports_ReportService
	 */
	public $reports;

	public function init()
	{
		parent::init();

		$this->groups  = Craft::app()->getComponent('sproutReports_reportGroup');
		$this->sources = Craft::app()->getComponent('sproutReports_dataSource');
		$this->exports = Craft::app()->getComponent('sproutReports_export');
		$this->reports = Craft::app()->getComponent('sproutReports_report');
	}

	/**
	 * @param string $words
	 *
	 * @throws Exception
	 * @return string
	 */
	public function createHandle($words)
	{
		$words = trim($words);

		if (empty($words))
		{
			throw new Exception(Craft::t('Cannot create handle from empty string.'));
		}

		$words = ElementHelper::createSlug($words);
		$words = explode('-', $words);
		$slug  = array_shift($words);

		if (count($words))
		{
			foreach ($words as $word)
			{
				$slug .= ucfirst($word);
			}
		}

		return $slug;
	}
}

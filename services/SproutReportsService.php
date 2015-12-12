<?php
namespace Craft;

class SproutReportsService extends BaseApplicationComponent
{
	/**
	 * @var SproutReports_ReportGroupsService
	 */
	public $reportGroups;

	/**
	 * @var SproutReports_DataSourcesService
	 */
	public $dataSources;

	/**
	 * @var SproutReports_ExportsService
	 */
	public $exports;

	/**
	 * @var SproutReports_ReportsService
	 */
	public $reports;

	public function init()
	{
		parent::init();

		$this->reportGroups = Craft::app()->getComponent('sproutReports_reportGroups');
		$this->dataSources = Craft::app()->getComponent('sproutReports_dataSources');
		$this->exports = Craft::app()->getComponent('sproutReports_exports');
		$this->reports = Craft::app()->getComponent('sproutReports_reports');
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

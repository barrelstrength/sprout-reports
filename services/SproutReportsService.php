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

	/**
	 * @var SproutReports_SettingsService
	 */
	public $settings;

	public function init()
	{
		parent::init();

		$this->reportGroups = Craft::app()->getComponent('sproutReports_reportGroups');
		$this->dataSources  = Craft::app()->getComponent('sproutReports_dataSources');
		$this->exports      = Craft::app()->getComponent('sproutReports_exports');
		$this->reports      = Craft::app()->getComponent('sproutReports_reports');
		$this->settings     = Craft::app()->getComponent('sproutReports_settings');
	}
}

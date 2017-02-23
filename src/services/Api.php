<?php
/**
 * Sprout Reports plugin for Craft CMS 3.x
 *
 * Powerful custom reports.
 *
 * @link      barrelstrengthdesign.com
 * @copyright Copyright (c) 2017 Barrelstrength
 */

namespace barrelstrength\sproutreports\services;

use barrelstrength\sproutreports\SproutReports;

use Craft;
use craft\base\Component;

/**
 * Api Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Barrelstrength
 * @package   SproutReports
 * @since     3
 */
class Api extends Component
{
	/**
	 * @var ReportGroups
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
		$this->reportGroups = new ReportGroups();
		$this->dataSources  = new DataSources();
		//$this->exports      = new Email();
		//$this->settings     = new Link();
	}
}

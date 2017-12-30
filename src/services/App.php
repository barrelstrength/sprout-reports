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

use barrelstrength\sproutbase\services\sproutreports\DataSources;
use barrelstrength\sproutbase\services\sproutreports\Exports;
use barrelstrength\sproutbase\services\sproutreports\Reports;
use craft\base\Component;

/**
 * App Service
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
class App extends Component
{
    /**
     * @var ReportGroups
     */
    public $reportGroups;

    /**
     * @var DataSources
     */
    public $dataSources;

    /**
     * @var Reports
     */
    public $reports;

    /**
     * @var Exports
     */
    public $exports;

    public function init()
    {
        $this->reportGroups = new ReportGroups();
        $this->dataSources = new DataSources();
        $this->reports = new Reports();
        $this->exports = new Exports();
    }
}

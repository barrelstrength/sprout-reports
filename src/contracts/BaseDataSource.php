<?php
namespace barrelstrength\sproutreports\contracts;

use Craft;
use barrelstrength\sproutreports\records\DataSource;
use barrelstrength\sproutreports\models\Report as ReportModel;
use barrelstrength\sproutreports\SproutReports;
use craft\base\Plugin;
use craft\helpers\UrlHelper;

/**
 * Class BaseDataSource
 *
 * @package Craft
 */
abstract class BaseDataSource
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $pluginName;

	/**
	 * @var string
	 */
	protected $pluginHandle;

	/**
	 * @var ReportModel()
	 */
	protected $report;

	/**
	 * @param string $pluginHandle
	 */
	public function __construct()
	{
		$namespaces = explode('\\', __NAMESPACE__);

		$class = basename(get_class($this));

		// get plugin name on second array
		$dataSourceClass = $namespaces[1] . '.' . $class;

		$this->id = strtolower($dataSourceClass);
	}

	/**
	 * Returns a fully qualified string that uniquely identifies the given data source
	 *
	 * @format {plugin}.{source}
	 * 1. {plugin} should be the lower case version of the plugin handle
	 * 3. {source} should be the lower case version of your data source without prefixes or suffixes
	 *
	 * @example
	 * - SproutFormsSubmissionsDataSource   > sproutforms.submissions
	 * - CustomQuery > sproutreports.customquery
	 *
	 * @return string
	 */
	final public function getId()
	{
		return $this->id;
	}

	/**
	 * Set a ReportModel on our data source.
	 *
	 * @param ReportModel|null $report
	 */
	public function setReport(ReportModel $report = null)
	{
		if (is_null($report))
		{
			$report = new ReportModel();
		}

		$this->report = $report;
	}

	/**
	 * Returns the CP URL for the given data source with the option to append to it once composed
	 *
	 * @legend
	 * Breaks apart the data source id and transforms its components into a URL friendly string
	 *
	 * @example
	 * sproutReports.customQuery > sproutreports/customquery
	 * sproutreports.customquery > sproutreports/customquery
	 *
	 * @see getId()
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	final public function getUrl($append = null)
	{
		$url = join('/', explode('.', $this->getId()));

		return UrlHelper::cpUrl(sprintf('sproutreports/reports/%s/%s', $url, ltrim($append, '/')));
	}

	/**
	 * Returns the name of the plugin name that the given data source is bundled with
	 *
	 * @param string $name
	 */
	final public function setPluginName($name)
	{
		$this->pluginName = $name;
	}

	/**
	 * @return string
	 */
	final public function getPluginName()
	{
		$namespaces = explode('\\', __NAMESPACE__);

		$pluginName = $namespaces[1];

		$plugin = Craft::$app->getPlugins()->getPlugin($pluginName);

		return $plugin->name;
	}

	/**
	 * @param string $handle
	 */
	final public function setPluginHandle($handle)
	{
		$this->pluginHandle = $handle;
	}

	/**
	 * @return string
	 */
	final public function getPluginHandle()
	{
		return $this->pluginHandle;
	}

	/**
	 * Returns the total count of reports created based on the given data source
	 *
	 * @return [type] [description]
	 */
	final public function getReportCount()
	{
		return SproutReports::$app->reports->getCountByDataSourceId($this->getId());
	}

	/**
	 * Should return a human readable name for your data source
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Should return an string containing the necessary HTML to capture user input
	 *
	 * @return null|string
	 */
	public function getOptionsHtml()
	{
		return null;
	}

	/**
	 * Should return an array of strings to be used as column headings in display/output
	 *
	 * @return array
	 */
	public function getDefaultLabels(ReportModel &$report, $options = array())
	{
		return array();
	}

	/**
	 * Should return an array of records to use in the report
	 *
	 * @param ReportModel $report
	 *
	 * @return null|array
	 */
	public function getResults(ReportModel &$report, $options = array())
	{
		return array();
	}

	/**
	 * Validate the data sources options
	 *
	 * @return boolean
	 */
	public function validateOptions(array $options = array(), array &$errors = array())
	{
		return true;
	}

	/**
	 * Allows a user to disable a Data Source from displaying in the New Report dropdown
	 *
	 * @return bool|mixed
	 */
	public function allowNew()
	{
		$record = DataSource::findOne(['dataSourceId' => $this->id]);

		// $record->allowNew != null
		if ($record != null)
		{
			return $record->allowNew;
		}

		return true;
	}

	/**
	 * Allow a user to toggle the Allow Html setting.
	 *
	 * @return bool
	 */
	public function isAllowHtmlEditable()
	{
		return false;
	}

	/**
	 * Define the default value for the Allow HTML setting. Setting Allow HTML
	 * to true enables a report to output HTML on the Results page.
	 *
	 * @return bool
	 */
	public function getDefaultAllowHtml()
	{
		return false;
	}
}

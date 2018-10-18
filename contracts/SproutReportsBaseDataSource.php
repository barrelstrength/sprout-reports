<?php
namespace Craft;

/**
 * Class SproutReportsBaseDataSource
 *
 * @package Craft
 */
abstract class SproutReportsBaseDataSource
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
	 * @var SproutReports_ReportModel()
	 */
	protected $report;

	/**
	 * @param string $pluginHandle
	 */
	final public function setId($pluginHandle)
	{
		$dataSourceClass = str_replace('Craft\\', '', get_class($this));

		$this->id = sproutReports()->dataSources->generateId($pluginHandle, $dataSourceClass);
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
	 * - SproutReportsQueryDataSource > sproutreports.customquery
	 *
	 * @return string
	 */
	final public function getId()
	{
		return $this->id;
	}

	/**
	 * Set a SproutReports_ReportModel on our data source.
	 *
	 * @param SproutReports_ReportModel|null $report
	 */
	public function setReport(SproutReports_ReportModel $report = null)
	{
		if (is_null($report))
		{
			$report = new SproutReports_ReportModel();
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

		return UrlHelper::getCpUrl(sprintf('sproutreports/reports/%s/%s', $url, ltrim($append, '/')));
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
		return $this->pluginName;
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
		return sproutReports()->reports->getCountByDataSourceId($this->getId());
	}

	/**
	 * Should return a human readable name for your data source
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Give a Data Source a chance to prepare options before they are processed by the Name Format field
	 *
	 * @param array $options
	 *
	 * @return null
	 */
	public function prepOptions(array $options)
	{
		return $options;
	}

	/**
	 * Should return an string containing the necessary HTML to capture user input
	 *
	 * @return null|string
	 */
    public function getOptionsHtml(array $options = array())
    {
		return null;
	}

	/**
	 * Should return an array of strings to be used as column headings in display/output
	 *
	 * @return array
	 */
	public function getDefaultLabels(SproutReports_ReportModel &$report, $options = array())
	{
		return array();
	}

	/**
	 * Should return an array of records to use in the report
	 *
	 * @param SproutReports_ReportModel $report
	 * @param array                     $options
	 * @return array
	 */
	public function getResults(SproutReports_ReportModel &$report, $options = array())
	{
		return array();
	}

	/**
	 * Validate the data sources options
	 *
	 * @return boolean
	 */
	public function validateOptions(array $options = array(), array $errors = array())
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
		$record = SproutReports_DataSourceRecord::model()->findByAttributes(array(
			'dataSourceId' => $this->id
		));

		if ($record != null && $record->allowNew != null)
		{
			return $record->allowNew;
		}

		return true;
	}

	/**
	 * Allow a user to toggle the Allow Html setting.
	 *
	 * @return null|string
	 */
	public function isAllowHtmlEditable()
	{
		return false;
	}

	/**
	 * Define the default value for the Allow HTML setting. Setting Allow HTML
	 * to true enables a report to output HTML on the Results page.
	 *
	 * @return null|string
	 */
	public function getDefaultAllowHtml()
	{
		return false;
	}
}

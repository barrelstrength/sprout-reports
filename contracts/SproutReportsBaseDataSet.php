<?php
namespace Craft;

/**
 * Class SproutReportsBaseDataSet
 *
 * @package Craft
 */
abstract class SproutReportsBaseDataSet
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @return string
	 */
	public final function getId()
	{
		if (is_null($this->id))
		{
			$this->id = explode('\\', get_class($this));
			$this->id = array_pop($this->id);
		}

		return $this->id;
	}

	/**
	 * Should return a human readable name for your data set
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Should return the name of the plugin this data set is bundled with
	 *
	 * @return string
	 */
	abstract public function getProviderName();

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
	 * Should return an array of records to use in the report
	 *
	 * @param SproutReportsBaseReportModel $report
	 *
	 * @return null|array
	 */
	abstract public function getResultSet(SproutReportsBaseReportModel $report);
}

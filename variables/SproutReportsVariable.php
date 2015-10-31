<?php
namespace Craft;

/**
 * Class SproutReportsVariable
 *
 * @package Craft
 */
class SproutReportsVariable
{
	/**
	 * @var SproutReportsPlugin
	 */
	protected $plugin;

	public function __construct()
	{
		$this->plugin = craft()->plugins->getPlugin('sproutreports');
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->plugin->getName();
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->plugin->getVersion();
	}

	/**
	 * @return SproutReportsBaseDataSource[]
	 */
	public function getDataSources()
	{
		return sproutReports()->sources->getAll();
	}

	/**
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getReports()
	{
		return sproutReports()->reports->getAll();
	}

	/**
	 * @return null|SproutReports_ReportGroupModel[]
	 */
	public function getReportGroups()
	{
		return sproutReports()->groups->getAll();
	}

	/**
	 * @param $id
	 *
	 * @return null|SproutReports_ReportGroupModel[]
	 */
	public function getReportsByGroupId($id)
	{
		return sproutReports()->reports->getAllByGroupId($id);
	}

	/**
	 * @param int $id
	 *
	 * @return SproutReports_ReportModel
	 */
	public function getReportById($id)
	{
		return sproutReports()->reports->get($id);
	}

	public function runReport($id, array $options = array())
	{
		return sproutReports()->reports->run($id, $options);
	}
}

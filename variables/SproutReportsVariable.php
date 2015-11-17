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

	// @todo - refactor and combine logic of how this works and how
	// SproutReportsController::actionRunReport() work
	public function runReport($id, array $options = array())
	{
		$report = sproutReports()->reports->get($id);

		if ($report)
		{

			$dataSource = sproutReports()->sources->get($report->dataSourceId);

			if ($dataSource)
			{
				$values = $dataSource->getResults($report);

				if (!empty($values) && empty($labels))
				{
					$labels = array_keys($values[0]);
				}

				return compact('values', 'labels');
			}
		}
	}
}

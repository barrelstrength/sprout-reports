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
		return sproutReports()->dataSources->getAllDataSources();
	}

	/**
	 * @return null|SproutReports_ReportModel[]
	 */
	public function getReports()
	{
		return sproutReports()->reports->getAllReports();
	}

	/**
	 * @return null|SproutReports_ReportGroupModel[]
	 */
	public function getReportGroups()
	{
		return sproutReports()->reportGroups->getAllReportGroups();
	}

	/**
	 * @param $id
	 *
	 * @return null|SproutReports_ReportGroupModel[]
	 */
	public function getReportsByGroupId($groupId)
	{
		return sproutReports()->reports->getReportsByGroupId($groupId);
	}

	/**
	 * @param int $id
	 *
	 * @return SproutReports_ReportModel
	 */
	public function getReportById($reportId)
	{
		return sproutReports()->reports->getReport($reportId);
	}

	public function getReportsAsSelectFieldOptions()
	{
		$options = array();
		$reports = $this->getReports();

		if ($reports)
		{
			foreach ($reports as $report)
			{
				$options[] = array(
					'label' => $report->name,
					'value' => $report->id,
				);
			}
		}
		return $options;
	}

	/**
	 * @param array $row
	 *
	 * @return bool
	 */
	public function addHeaderRow(array $row)
	{
		sproutReports()->twigDataSource->addHeaderRow($row);
	}

	/**
	 * Add a single row of data to your report
	 *
	 * @example array()
	 *
	 * @param array $row
	 *
	 * @return null
	 */
	public function addRow(array $row)
	{
		sproutReports()->twigDataSource->addRow($row);
	}

	/**
	 * Add multiple rows of data to your report
	 *
	 * @example array(
	 *   array( ... ),
	 *   array( ... )
	 * )
	 *
	 * @param array $rows
	 */
	public function addRows(array $rows)
	{
		sproutReports()->twigDataSource->addRows($rows);
	}

	// @todo - figure out how this best works with Labels and Values before making available
	//public function getResults($reportHandle, array $options = array())
	//{
	//	$report = sproutReports()->reports->getReportByHandle($reportHandle);
	//
	//	if ($report)
	//	{
	//		$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);
	//
	//		if ($dataSource)
	//		{
	//			$values = $dataSource->getResults($report);
	//
	//			if (!empty($values) && empty($labels))
	//			{
	//				$firstItemInArray = reset($values);
	//				$labels = array_keys($firstItemInArray);
	//			}
	//
	//			return compact('labels', 'values');
	//		}
	//	}
	//}
}

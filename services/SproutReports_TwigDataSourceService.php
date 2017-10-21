<?php
namespace Craft;

/**
 * Class SproutReports_TwigDataSourceService
 *
 * @package Craft
 */
class SproutReports_TwigDataSourceService extends BaseApplicationComponent
{
	/**
	 * Determine if a results template has been run already
	 *
	 * @var $hasRun
	 */
	public $hasRun;

	/**
	 * A single array representing the column headers of the first row of a report
	 *
	 * @var $labels
	 */
	public $labels;

	/**
	 * Variable that is used to build reports row by row
	 *
	 *  array
	 * (
	 *   0 => array
	 *   (
	 * 		'column' => 1,
	 * 		'column2' => 2
	 *   ),
	 * 	 1 => array
	 * 	 (
	 * 	  'column' => 1,
	 * 		'column2' => 2
	 * 	 )
	 * );
   *
	 * @var $rows
	 */
	public $rows;

	/**
	 * @param array $row
	 *
	 * @return bool
	 */
	public function addHeaderRow(array $row)
	{
		$this->labels = $row;
	}

	/**
	 * Add a single row of data to your report
	 *
	 * @example array()
	 *
	 * @param array $row
	 *
	 * @return bool
	 */
	public function addRow(array $row)
	{
		$this->rows[] = $row;
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
	 *
	 * @return bool
	 */
	public function addRows(array $rows)
	{
		foreach ($rows as $key => $row)
		{
			$this->addRow($row);
		}
	}
}

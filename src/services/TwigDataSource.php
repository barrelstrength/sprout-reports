<?php

namespace barrelstrength\sproutreports\services;

use craft\base\Component;

/**
 * Class SproutReports_TwigDataSourceService
 *
 * @package Craft
 */
class TwigDataSource extends Component
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
    public $labels = [];

    /**
     * Variable that is used to build reports row by row
     *
     * @example array
     * (
     *   0 => array
     *   (
     *        'column' => 1,
     *        'column2' => 2
     *   ),
     *     1 => array
     *     (
     *      'column' => 1,
     *        'column2' => 2
     *     )
     * );
     *
     * @var $rows array
     */
    public $rows = [];

    /**
     * @param array $row
     */
    public function addHeaderRow(array $row)
    {
        $this->labels = $row;
    }

    /**
     * @param array $row
     */
    public function addRow(array $row)
    {
        $this->rows[] = $row;
    }

    /**
     * Add multiple rows of data to your report
     *
     * @param array $rows
     *
     * @example array(
     *          array( ... ),
     *          array( ... )
     *          )
     *
     */
    public function addRows(array $rows)
    {
        foreach ($rows as $key => $row) {
            $this->addRow($row);
        }
    }
}

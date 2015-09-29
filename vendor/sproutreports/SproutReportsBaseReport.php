<?php
namespace Craft;

abstract class SproutReportsBaseReport {
	/*
	 * Get report group
	 * @return string
	 */
	abstract public function getGroup();

	/*
	 * Get report name
	 * @return string
	 */
	abstract public function getName();

	/*
	 * SQL query to be used for generating reports
	 * @return string
	 */
	abstract public function getQuery();

	/*
	 * Define user options for report
	 * @return array
	 */
	abstract public function getUserOptions();

	/*
	 * Is custom query editable or not
	 * @return boolean
	 */
	abstract public function getIsCustomQueryEditable();

	/*
	 * Prepare query params to make it usable in sql query
	 * @var $params array
	 * @return array $params[$optionName] = $optionValue
	 */
	abstract public static function prepareQueryParams($params);

}
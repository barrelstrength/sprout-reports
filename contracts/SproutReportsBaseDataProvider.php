<?php
namespace Craft;

/**
 * Class SproutReportsBaseDataProvider
 *
 * @package Craft
 */
abstract class SproutReportsBaseDataProvider
{
	/**
	 * Should return a human friendly and translatable string
	 *
	 * @return string (My Data Provider)
	 */
	abstract public function getName();

	/**
	 * Should return an array of data sets from which reports can be created
	 *
	 * @note: Data set classes in the array must extend SproutReportsDataSet
	 *
	 * @return array
	 */
	abstract public function getDataSets();
}

<?php
namespace barrelstrength\sproutreports;

/**
 * Class SproutReportsBaseReport
 *
 * @package Craft
 */
abstract BaseReport
{
	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @return string
	 */
	abstract public function getHandle();

	/**
	 * @return string
	 */
	abstract public function getGroupName();

	/**
	 * @return string
	 */
	abstract public function getDescription();

	/**
	 * @return array
	 */
	abstract public function getOptions();

	/**
	 * @return SproutReportsBaseDataSource
	 */
	abstract public function getDataSource();
}

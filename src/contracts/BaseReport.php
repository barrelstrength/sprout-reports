<?php
namespace barrelstrength\sproutreports\contracts;

/**
 * Class SproutReportsBaseReport
 *
 * @package Craft
 */
abstract class BaseReport
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
	 * @return BaseDataSource
	 */
	abstract public function getDataSource();
}

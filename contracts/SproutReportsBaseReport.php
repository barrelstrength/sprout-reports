<?php
namespace Craft;

/**
 * Class SproutReportsBaseReport
 *
 * @package Craft
 */
abstract class SproutReportsBaseReport
{
	/**
	 * @return string
	 */
	final public function getSlug()
	{
		return ElementHelper::createSlug($this->getHandle());
	}

	/**
	 * @return string
	 */
	final public function getHandle()
	{
		return sproutReports()->createHandle($this->getName());
	}

	/**
	 * @return string
	 */
	abstract public function getName();

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
	abstract public function getSettings();

	/**
	 * @return array
	 */
	abstract public function getOptions();

	/**
	 * @return SproutReportsBaseDataSource
	 */
	abstract public function getDataSource();
}

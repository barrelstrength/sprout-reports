<?php
namespace Craft;

/**
 * Class SproutReportsBaseReportModel
 *
 * @package Craft
 */
abstract class SproutReportsBaseReportModel
{
	/**
	 * @return string
	 */
	final public function getHandle()
	{
		$slug   = ElementHelper::createSlug($this->getName());
		$slug   = explode('-', $slug);
		$handle = array_shift($slug);

		if ($slug)
		{
			foreach ($slug as $word)
			{
				$handle .= ucfirst($word);
			}
		}

		return $handle;
	}

	/**
	 * @return string
	 */
	abstract public function getName();

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
	 * @return string
	 */
	abstract public function getDataSetId();

	/**
	 * Whether or not the result set is a scalar value
	 *
	 * Scalar: The value returned will be the first column in the first row of the query result
	 *
	 * @return bool
	 */
	public function isScalar()
	{
		return false;
	}
}

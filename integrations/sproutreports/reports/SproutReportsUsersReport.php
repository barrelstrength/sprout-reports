<?php
namespace Craft;

class SproutReportsUsersReport extends SproutReportsBaseReport
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'All user data';
	}

	/**
	 * @return string
	 */
	public function getGroupName()
	{
		return 'Sprout Reports';
	}

	public function getDescription()
	{
		return 'Returns a list of all users restricted by options selected.';
	}

	public function getSettings()
	{
		return array();
	}

	public function getOptions()
	{
		return array(
			'pendingOnly' => false,
		);
	}

	public function getDataSource()
	{
		return sproutReports()->sources->get('sproutreports.users');
	}
}

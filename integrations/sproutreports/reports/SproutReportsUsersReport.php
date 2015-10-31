<?php
namespace Craft;

class SproutReportsUsersReport extends SproutReportsBaseReport
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Pending Users';
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
		return 'Returns a subset of users that have not activated their account.';
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

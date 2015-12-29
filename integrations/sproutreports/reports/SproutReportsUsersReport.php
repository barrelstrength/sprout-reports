<?php
namespace Craft;

class SproutReportsUsersReport extends SproutReportsBaseReport
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Users and User Groups';
	}

	public function getHandle()
	{
		return 'usersAndUserGroups';
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
		return 'Create a list of all users and their user groups.';
	}

	public function getOptions()
	{
		return array(
			'userGroups' => '*',
			'displayUserGroupColumns' => true
		);
	}

	public function getDataSource()
	{
		return sproutReports()->dataSources->getDataSourceById('sproutreports.users');
	}
}

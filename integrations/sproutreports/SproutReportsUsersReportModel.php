<?php
namespace Craft;

class SproutReportsUsersReportModel extends SproutReportsBaseReportModel
{
	public function getName()
	{
		return 'Pending Users';
	}

	public function getDescription()
	{
		return 'Returns a subset of users that have not activated their account.';
	}

	public function getSettings()
	{
		return array(
			'isSingleNumber' => false,
		);
	}

	public function getOptions()
	{
		return array(
			'pending' => true,
		);
	}

	public function getGroup()
	{
		return 'Sprout Reports';
	}

	public function getDataSetId()
	{
		return 'SproutReportsUsersDataSet';
	}
}

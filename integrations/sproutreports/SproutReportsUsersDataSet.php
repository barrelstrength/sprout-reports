<?php
namespace Craft;

class SproutReportsUsersDataSet extends SproutReportsBaseDataSet
{
	public function getName()
	{
		return Craft::t('Users');
	}

	public function getProviderName()
	{
		return Craft::t('Sprout Reports');
	}

	public function getDescription()
	{
		return Craft::t('Returns a subset of users in your Craft install');
	}

	public function getResultSet(SproutReportsBaseReportModel $report)
	{
		return array(
			array(
				'firstName' => 'Selvin',
				'lastName'  => 'Ortiz',
				'email'     => 'selvin@selvin.co',
				'isPending' => 1,
			),
			array(
				'firstName' => 'John',
				'lastName'  => 'Smith',
				'email'     => 'john@smith.com'
			),
		);
	}

	public function getOptionsHtml()
	{

	}
}

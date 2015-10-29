<?php
namespace Craft;

class SproutReportsCustomQueryDataSet extends SproutReportsBaseDataSet
{
	public function getName()
	{
		return Craft::t('Custom Query');
	}

	public function getProviderName()
	{
		return Craft::t('Sprout Reports');
	}

	public function getDescription()
	{
		return Craft::t('Returns whatever you tell it to, you just have to know how to write SQL');
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

	public function getOptionsHtml(array $options = array())
	{
		return craft()->templates->render('sproutreports/_harmony/reports/options/custom-query', compact('options'));
	}
}

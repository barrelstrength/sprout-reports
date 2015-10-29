<?php
namespace Craft;

class SproutReportsDataProvider extends SproutReportsBaseDataProvider
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Sprout Reports');
	}

	/**
	 * @return SproutReportsBaseDataSet
	 */
	public function getDataSets()
	{
		return array(
			new SproutReportsUsersDataSet(),
			new SproutReportsEntriesDataSet(),
			new SproutReportsCustomQueryDataSet(),
		);
	}
}

<?php
namespace Craft;

class SproutReportsUsersDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Users');
	}

	public function getDescription()
	{
		return Craft::t('Returns a list of all users filtered by options in report.');
	}

	/**
	 * @param  SproutReports_ReportModel &$report
	 *
	 * @return array|null
	 */
	public function getResults(SproutReports_ReportModel &$report)
	{
		$fields = array('id', 'email', 'firstName', 'lastName');
		$criteria = craft()->elements->getCriteria(ElementType::User);
		$criteria->limit = null;
		$criteria->groups = array(1);

		$filter = function($user) use ($fields)
		{
			return $user->getAttributes($fields);
		};

		return array_map(
			$filter,
			$criteria->find()
		);
	}

	/**
	 * @param array $options
	 *
	 * @return string
	 */
	public function getOptionsHtml(array $options = array())
	{
		return craft()->templates->render('sproutreports/_reports/options/users', compact('options'));
	}
}

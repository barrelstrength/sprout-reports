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
		$options = $report->getOptions();

		$fields = array('id', 'email', 'firstName', 'lastName');
		$criteria = craft()->elements->getCriteria(ElementType::User);
		$criteria->limit = null;
		$criteria->groupId = $options['memberGroups'];

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
		$userGroups = craft()->userGroups->getAllGroups();

		//$userGroupOptions[] = array(
		//	'label' => 'Admin',
		//	'value' => 'admin'
		//);

		foreach ($userGroups as $userGroup)
		{
			$userGroupOptions[] = array(
				'label' => $userGroup->name,
				'value' => $userGroup->id
			);
		}

		$optionErrors = array_shift($this->report->getErrors('options'));

		return craft()->templates->render('sproutreports/datasources/_options/users', array(
			'userGroupOptions' => $userGroupOptions,
			'options' => $this->report->getOptions(),
			'errors' => $optionErrors
		));
	}

	/**
	 * Validate our data source options
	 *
	 * @param array $options
	 * @return array|bool
	 */
	public function validate(array $options = array())
	{
		$errors = null;

		if (empty($options['memberGroups']))
		{
			$errors['memberGroups'][] = Craft::t('Select at least one Member Group.');

			return $errors;
		}

		return true;
	}
}

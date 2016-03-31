<?php
namespace Craft;

class SproutReportsCategoriesDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Category Usage by Section');
	}

	public function getDescription()
	{
		return Craft::t('Returns a breakdown of Categories used by Entries.');
	}

	/**
	 * @param  SproutReports_ReportModel &$report
	 *
	 * @return array|null
	 */
	public function getResults(SproutReports_ReportModel &$report, $options)
	{
		$options = $report->getOptions();

		$sectionId       = $options['sectionId'];
		$categoryGroupId = $options['categoryGroupId'];

		$criteria          = craft()->elements->getCriteria(ElementType::Category);
		$criteria->limit   = null;
		$criteria->groupId = $categoryGroupId;

		$categories  = $criteria->find(array('indexBy' => 'id'));
		$categoryIds = array_keys($categories);

		$criteria            = craft()->elements->getCriteria(ElementType::Entry);
		$criteria->sectionId = $sectionId;
		$entryIds            = $criteria->ids();

		$totalCategories = craft()->db->createCommand()
			->select('COUNT(*)')
			->from('relations')
			->where(array('in', "{{relations.sourceId}}", $entryIds))
			->andWhere(array('in', "{{relations.targetId}}", $categoryIds))
			->queryScalar();

		$entries = craft()->db->createCommand()
			->select("{{content.title}} AS 'Category Name',
								COUNT({{relations.sourceId}}) AS 'Total Entries Assigned Category',
								(COUNT({{relations.sourceId}}) / $totalCategories) * 100 AS '% of Total Categories used'
				")
			->from('content')
			->join('categories', '{{content.elementId}} = {{categories.id}}')
			->join('relations', '{{relations.targetId}} = {{categories.id}}')
			->where(array('in', "{{relations.sourceId}}", $entryIds))
			->andWhere(array('in', "{{relations.targetId}}", $categoryIds))
			->group('{{relations.targetId}}')
			->queryAll();

		return $entries;
	}

	/**
	 * @param array $options
	 *
	 * @return string
	 */
	public function getOptionsHtml(array $options = array())
	{
		$sectionOptions       = array();
		$categoryGroupOptions = array();

		$sections = craft()->sections->getAllSections();

		foreach ($sections as $section)
		{
			if ($section->type != 'single')
			{
				$sectionOptions[] = array(
					'label' => $section->name,
					'value' => $section->id
				);
			}
		}

		$categoryGroups = craft()->categories->getAllGroups();

		foreach ($categoryGroups as $categoryGroup)
		{
			$categoryGroupOptions[] = array(
				'label' => $categoryGroup->name,
				'value' => $categoryGroup->id
			);
		}

		$optionErrors = $this->report->getErrors('options');
		$optionErrors = array_shift($optionErrors);

		$setupRequiredMessage = null;

		if (empty($sectionOptions) OR empty($categoryGroupOptions))
		{
			$setupRequiredMessage = Craft::t('This report requires a Channel or Structure section using Categories. Please update your settings to include at least one Channel or Structure and at least one Category Group with Categories available to assign to that section.');
		}

		return craft()->templates->render('sproutreports/datasources/_options/categories', array(
			'options'              => count($options) ? $options : $this->report->getOptions(),
			'sectionOptions'       => $sectionOptions,
			'categoryGroupOptions' => $categoryGroupOptions,
			'errors'               => $optionErrors,
			'setupRequiredMessage' => $setupRequiredMessage
		));
	}

	/**
	 * Validate our data source options
	 *
	 * @param array $options
	 * @return array|bool
	 */
	public function validateOptions(array $options = array(), array $errors = array())
	{
		if (empty($options['sectionId']))
		{
			$errors['sectionId'][] = Craft::t('Section is required.');
		}

		if (empty($options['categoryGroupId']))
		{
			$errors['categoryGroupId'][] = Craft::t('Category Group is required.');
		}

		if (count($errors) > 0)
		{
			return false;
		}

		return true;
	}
}

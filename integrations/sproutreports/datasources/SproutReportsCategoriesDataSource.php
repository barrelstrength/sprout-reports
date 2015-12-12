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

	public function getDefaultLabels()
	{
		return array(
			'Category Name',
			'Total Entries Assigned Category',
			'% of Total Categories'
		);
	}

	/**
	 * @param  SproutReports_ReportModel &$report
	 *
	 * @return array|null
	 */
	public function getResults(SproutReports_ReportModel &$report)
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
								COUNT({{relations.sourceId}}) AS 'ASDASD Entries assigned this category',
								(COUNT({{relations.sourceId}}) / $totalCategories) AS '% of total categories used'
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
		$errorMessage         = '';

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

		if (empty($sectionOptions) or empty($categoryGroupOptions))
		{
			$errorMessage = Craft::t('This report requires a Channel or Structure section using Categories. Please update your settings to include at least one Channel or Structure and at least one Category Group with Categories available to assign to that section.');
		}

		return craft()->templates->render('sproutreports/datasources/_options/categories', array(
			'options'              => $options,
			'sectionOptions'       => $sectionOptions,
			'categoryGroupOptions' => $categoryGroupOptions,
			'errorMessage'         => $errorMessage
		));
	}
}

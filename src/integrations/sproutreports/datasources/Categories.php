<?php
namespace barrelstrength\sproutreports\integrations\sproutreports\datasources;

use barrelstrength\sproutcore\integrations\sproutreports\contracts\BaseDataSource;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutreports\models\Report as ReportModel;
use craft\records\Category as CategoryRecord;
use craft\records\Entry as EntryRecord;
use craft\db\Query;

class Categories extends BaseDataSource
{
	public function getName()
	{
		return SproutReports::t('Category Usage by Section');
	}

	public function getDescription()
	{
		return SproutReports::t('Returns a breakdown of Categories used by Entries.');
	}

	/**
	 * @param  ReportModel &$report
	 *
	 * @return array|null
	 */
	public function getResults(ReportModel &$report, $options = array())
	{
		$options = $report->getOptions();

		$sectionId       = $options->sectionId;
		$categoryGroupId = $options->categoryGroupId;

		$categoryQuery = CategoryRecord::find()
			->where(['groupId' => $categoryGroupId])
			->indexBy('id')
			->all();

		$categoryIds = array_keys($categoryQuery);

		$entryQuery = EntryRecord::find()
			->where(['sectionId' => $sectionId])
			->indexBy('id')
			->all();

		$entryIds = array_keys($entryQuery);

		$query = new Query();
		$totalCategories =  $query
			->select('COUNT(*)')
			->from('relations')
			->where(array('in', "relations.sourceId", $entryIds))
			->andWhere(array('in', "relations.targetId", $categoryIds))
			->scalar();

		$query = new Query();
		$entries = $query
			->select("(content.title) AS 'Category Name',
			COUNT(relations.sourceId) AS 'Total Entries Assigned Category',
								(COUNT(relations.sourceId) / $totalCategories) * 100 AS '% of Total Categories used'
			")
			->from('content')
			->join('LEFT JOIN', 'categories', 'content.elementId = categories.id')
			->join('LEFT JOIN', 'relations', 'relations.targetId = categories.id')
			->where(array('in', "relations.sourceId", $entryIds))
			->andWhere(array('in', "relations.targetId", $categoryIds))
			->groupBy('relations.targetId')
			->all();

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

		$sections = \Craft::$app->getSections()->getAllSections();

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

		$categoryGroups = \Craft::$app->getCategories()->getAllGroups();

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
			$setupRequiredMessage = SproutReports::t('This report requires a Channel or Structure section using Categories. Please update your settings to include at least one Channel or Structure and at least one Category Group with Categories available to assign to that section.');
		}

		return \Craft::$app->getView()->renderTemplate('sproutreports/datasources/_options/categories', array(
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
	public function validateOptions(array $options = array(), array &$errors = array())
	{
		if (empty($options['sectionId']))
		{
			$errors['sectionId'][] = SproutReports::t('Section is required.');
		}

		if (empty($options['categoryGroupId']))
		{
			$errors['categoryGroupId'][] = SproutReports::t('Category Group is required.');
		}

		if (count($errors) > 0)
		{
			return false;
		}

		return true;
	}
}

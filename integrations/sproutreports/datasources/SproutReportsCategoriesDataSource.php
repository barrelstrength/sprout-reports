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
				'Total Entries assigned this category',
				'% of total categories used'
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

		$sectionId = $options['sectionId'];
		$categoryGroupId = $options['categoryGroupId'];


		$criteria = craft()->elements->getCriteria(ElementType::Category);
		$criteria->limit = null;
		$criteria->groupId = $categoryGroupId;

		$categories = $criteria->find(array('indexBy'=>'id'));
		$categoryIds = array_keys($categories);

		$criteria = craft()->elements->getCriteria(ElementType::Entry);
		$criteria->sectionId = $sectionId;
		$entryIds = $criteria->ids();

		$totalCategories = craft()->db->createCommand()
				->select('COUNT(*)')
				->from('relations')
				->where(array('in', "{{relations.sourceId}}", $entryIds))
				->andWhere(array('in', "{{relations.targetId}}", $categoryIds))
				->queryScalar();

		$entries = craft()->db->createCommand()
				->select("{{content.title}} AS 'Category Name',
									COUNT({{relations.sourceId}}) AS 'Total Entries assigned this category',
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
		return craft()->templates->render('sproutreports/datasources/_options/categories', compact('options'));
	}
}

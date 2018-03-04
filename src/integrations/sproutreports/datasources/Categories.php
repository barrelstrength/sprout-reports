<?php

namespace barrelstrength\sproutreports\integrations\sproutreports\datasources;

use barrelstrength\sproutbase\contracts\sproutreports\BaseDataSource;
use barrelstrength\sproutbase\models\sproutreports\Report as ReportModel;
use craft\records\Category as CategoryRecord;
use craft\records\Entry as EntryRecord;
use craft\db\Query;
use Craft;

class Categories extends BaseDataSource
{
    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('sprout-reports', 'Category Usage by Section');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Craft::t('sprout-reports', 'Returns a breakdown of Categories used by Entries.');
    }

    /**
     * @param ReportModel $report
     * @param array       $paramOptions
     *
     * @return array
     */
    public function getResults(ReportModel $report, array $paramOptions = [])
    {
        $options = $report->getOptions();

        $sectionId = $options->sectionId;
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
        $totalCategories = $query
            ->select('COUNT(*)')
            ->from('relations')
            ->where(['in', 'relations.sourceId', $entryIds])
            ->andWhere(['in', 'relations.targetId', $categoryIds])
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
            ->where(['in', 'relations.sourceId', $entryIds])
            ->andWhere(['in', 'relations.targetId', $categoryIds])
            ->groupBy('relations.targetId')
            ->all();

        return $entries;
    }

    /**
     * @param array $options
     *
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getOptionsHtml(array $options = [])
    {
        $sectionOptions = [];
        $categoryGroupOptions = [];

        $sections = \Craft::$app->getSections()->getAllSections();

        foreach ($sections as $section) {
            if ($section->type != 'single') {
                $sectionOptions[] = [
                    'label' => $section->name,
                    'value' => $section->id
                ];
            }
        }

        $categoryGroups = \Craft::$app->getCategories()->getAllGroups();

        foreach ($categoryGroups as $categoryGroup) {
            $categoryGroupOptions[] = [
                'label' => $categoryGroup->name,
                'value' => $categoryGroup->id
            ];
        }

        $optionErrors = $this->report->getErrors('options');
        $optionErrors = array_shift($optionErrors);

        $setupRequiredMessage = null;

        if (empty($sectionOptions) OR empty($categoryGroupOptions)) {
            $setupRequiredMessage = Craft::t('sprout-reports', 'This report requires a Channel or Structure section using Categories. Please update your settings to include at least one Channel or Structure and at least one Category Group with Categories available to assign to that section.');
        }

        return \Craft::$app->getView()->renderTemplate('sprout-reports/datasources/_options/categories', [
            'options' => count($options) ? $options : $this->report->getOptions(),
            'sectionOptions' => $sectionOptions,
            'categoryGroupOptions' => $categoryGroupOptions,
            'errors' => $optionErrors,
            'setupRequiredMessage' => $setupRequiredMessage
        ]);
    }

    /**
     * Validate our data source options
     *
     * @param array $options
     * @param array $errors
     *
     * @return bool
     */
    public function validateOptions(array $options = [], array &$errors)
    {
        if (empty($options['sectionId'])) {
            $errors['sectionId'][] = Craft::t('sprout-reports', 'Section is required.');
        }

        if (empty($options['categoryGroupId'])) {
            $errors['categoryGroupId'][] = Craft::t('sprout-reports', 'Category Group is required.');
        }

        if (count($errors) > 0) {
            return false;
        }

        return true;
    }
}

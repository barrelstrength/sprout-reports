<?php

namespace barrelstrength\sproutreports\integrations\sproutreports\datasources;

use barrelstrength\sproutbase\contracts\sproutreports\BaseDataSource;
use Craft;
use barrelstrength\sproutbase\models\sproutreports\Report as ReportModel;

/**
 * Class SproutReportsQueryDataSource
 *
 * @package Craft
 */
class CustomQuery extends BaseDataSource
{
    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('sprout-reports', 'Custom Query');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Craft::t('sprout-reports', 'Create reports using a custom database query');
    }

    /**
     * @return bool
     */
    public function isAllowHtmlEditable()
    {
        return true;
    }

    /**
     * @param ReportModel $report
     * @param array       $options
     *
     * @return array
     */
    public function getResults(ReportModel $report, array $options = [])
    {
        $query = $report->getOption('query');

        $result = [];

        try {
            $result = Craft::$app->getDb()->createCommand($query)->queryAll();
        } catch (\Exception $e) {
            $report->setResultsError($e->getMessage());
        }

        return $result;
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
        $optionErrors = $this->report->getErrors('options');
        $optionErrors = array_shift($optionErrors);

        return Craft::$app->getView()->renderTemplate('sprout-reports/datasources/_options/query', [
            'options' => count($options) ? $options : $this->report->getOptions(),
            'errors' => $optionErrors
        ]);
    }

    /**
     * @param array $options
     * @param array $errors
     *
     * @return bool
     */
    public function validateOptions(array $options = [], array &$errors)
    {
        if (empty($options['query'])) {
            $errors['query'][] = Craft::t('sprout-reports', 'Query cannot be blank.');

            return false;
        }

        return true;
    }
}

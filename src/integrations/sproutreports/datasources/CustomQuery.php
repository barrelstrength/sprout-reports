<?php

namespace barrelstrength\sproutreports\integrations\sproutreports\datasources;

use barrelstrength\sproutbase\contracts\sproutreports\BaseDataSource;
use Craft;
use barrelstrength\sproutbase\models\sproutreports\Report as ReportModel;
use barrelstrength\sproutreports\SproutReports;

/**
 * Class SproutReportsQueryDataSource
 *
 * @package Craft
 */
class CustomQuery extends BaseDataSource
{
    public function getName()
    {
        return SproutReports::t('Custom Query');
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return SproutReports::t('Create reports using a custom database query');
    }

    /**
     * @return bool
     */
    public function isAllowHtmlEditable()
    {
        return true;
    }

    /**
     * @todo:so Let's bring back a little sanity checks back into raw queries
     *
     * @param ReportModel $report
     *
     * @return array
     */
    public function getResults(ReportModel &$report, $options = [])
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
     * @return string
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
     *
     * @return bool
     */
    public function validateOptions(array $options = [], array &$errors = [])
    {
        if (empty($options['query'])) {
            $errors['query'][] = SproutReports::t('Query cannot be blank.');

            return false;
        }

        return true;
    }
}

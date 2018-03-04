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
     * @param array       $settings
     *
     * @return array
     */
    public function getResults(ReportModel $report, array $settings = [])
    {
        $query = $report->getSetting('query');

        $result = [];

        try {
            $result = Craft::$app->getDb()->createCommand($query)->queryAll();
        } catch (\Exception $e) {
            $report->setResultsError($e->getMessage());
        }

        return $result;
    }

    /**
     * @param array $settings
     *
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml(array $settings = [])
    {
        $settingsErrors = $this->report->getErrors('settings');
        $settingsErrors = array_shift($settingsErrors);

        return Craft::$app->getView()->renderTemplate('sprout-reports/datasources/_settings/query', [
            'settings' => count($settings) ? $settings : $this->report->getSettings(),
            'errors' => $settingsErrors
        ]);
    }

    /**
     * @param array $settings
     * @param array $errors
     *
     * @return bool
     */
    public function validateSettings(array $settings = [], array &$errors)
    {
        if (empty($settings['query'])) {
            $errors['query'][] = Craft::t('sprout-reports', 'Query cannot be blank.');

            return false;
        }

        return true;
    }
}

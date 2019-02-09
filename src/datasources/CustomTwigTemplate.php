<?php

namespace barrelstrength\sproutreports\datasources;

use barrelstrength\sproutbasereports\base\DataSource;
use barrelstrength\sproutbasereports\elements\Report;
use barrelstrength\sproutreports\SproutReports;
use Craft;
use craft\helpers\DateTimeHelper;

/**
 * Class SproutReportsTwigDataSource
 *
 * @package Craft
 */
class CustomTwigTemplate extends DataSource
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Craft::t('sprout-reports', 'Twig Template');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Craft::t('sprout-reports', 'Create a report using Twig in your templates folder.');
    }

    /**
     * @inheritdoc
     */
    public function isAllowHtmlEditable()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultLabels(Report $report, array $settings = [])
    {
        if (!SproutReports::$app->twigDataSource->hasRun) {
            $this->processFrontEndResultsTemplate($report, $settings);
            SproutReports::$app->twigDataSource->hasRun = true;
        }

        $labels = SproutReports::$app->twigDataSource->labels;

        if (count($labels)) {
            return $labels;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getResults(Report $report, array $settings = [])
    {
        if (!SproutReports::$app->twigDataSource->hasRun) {
            $this->processFrontEndResultsTemplate($report, $settings);
            SproutReports::$app->twigDataSource->hasRun = true;
        }

        $rows = SproutReports::$app->twigDataSource->rows;

        $this->processHeaderRow($rows);

        if (count($rows)) {
            return $rows;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(array $settings = [])
    {
        $settingsErrors = $this->report->getErrors('settings');
        $settingsErrors = array_shift($settingsErrors);

        // @todo - refactor?
        // We pass $settings to this method from the template, but the settings
        // may already exist on the report.... maybe we can simplify?
        $settings = count($settings) ? array_merge($settings, $this->report->getSettings()) : $this->report->getSettings();

        $customSettingsHtml = null;

        // If settings template exists as setting, look for it on the front-end.
        // If not, return a nice message explain how to handle settings.
        if (isset($settings['settingsTemplate']) && $settings['settingsTemplate'] != '') {
            $customSettingsTemplatePath = Craft::$app->getPath()->getSiteTemplatesPath().'/'.$settings['settingsTemplate'];

            $customSettingsFileContent = null;

            foreach (Craft::$app->getConfig()->getGeneral()->defaultTemplateExtensions as $extension) {
                if (file_exists($customSettingsTemplatePath.'.'.$extension)) {
                    $customSettingsFileContent = file_get_contents($customSettingsTemplatePath.'.'.$extension);
                    break;
                }
            }

            if (null !== $customSettingsFileContent) {
                // Add support for processing Template Settings by including Craft CP Form Macros and
                // wrapping all settings fields in the `settings` namespace
                $customSettingsHtmlWithExtras = $customSettingsFileContent;

                $settings = $this->prepSettings($settings);

                $customSettingsHtml = Craft::$app->getView()->renderString($customSettingsHtmlWithExtras, [
                    'settings' => count($settings) ? $settings : $this->report->getSettings(),
                    'errors' => $settingsErrors
                ]);
            }
        }

        return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/datasources/CustomTwigTemplate/settings', [
            'settings' => $this->report->getSettings(),
            'errors' => $settingsErrors,
            'settingsContents' => $customSettingsHtml ?? null
        ]);
    }

    /**
     * @inheritdoc
     */
    public function prepSettings(array $settings)
    {
        foreach ($settings as $name => $setting) {
            $datetime = strpos($name, 'datetime');

            // Date time field
            if ($datetime === 0) {
                $value = DateTimeHelper::toDateTime($settings[$name]);
                $settings[$name] = $value;
            }
        }

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function validateSettings(array $settings = [], array &$errors)
    {
        if (empty($settings['resultsTemplate'])) {
            $errors['resultsTemplate'][] = Craft::t('sprout-reports', 'Results template cannot be blank.');
        }

        if (count($errors)) {
            return false;
        }

        return true;
    }

    /**
     * Make sure we only process our template once.
     * Since we need data from the template in both the getDefaultLabels and getResults
     * methods we have to check in both places
     *
     * @param Report $report
     * @param array       $settings
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function processFrontEndResultsTemplate(Report $report, array $settings = [])
    {
        $resultsTemplate = $report->getSetting('resultsTemplate');

        $view = Craft::$app->getView();

        $view->setTemplateMode($view::TEMPLATE_MODE_SITE);

        $settings = count($settings) ? $settings : $report->getSettings();
        $settings = $this->prepSettings($settings);

        // Process our front-end Results template which adds Labels and Rows to:
        // sproutReports()->reports->twigReportLabels;
        // sproutReports()->reports->twigReportRows;

        $view->renderTemplate($resultsTemplate, [
            'settings' => $settings
        ]);

        $view->setTemplateMode($view::TEMPLATE_MODE_CP);
    }

    /**
     * @param $rows
     */
    public function processHeaderRow(&$rows)
    {
        $labels = SproutReports::$app->twigDataSource->labels;

        // If we don't have default labels, we will use the first row as for our column headers
        // We do so by making the first row the keys of the second row
        if (empty($labels) && count($rows)) {
            $headerRow = [];

            /**
             * @var $firstRowColumns array
             */
            $firstRowColumns = array_shift($rows);

            if (count($firstRowColumns)) {
                $secondRow = array_shift($rows);

                foreach ($firstRowColumns as $key => $column) {
                    $headerRow[$column] = $secondRow[$key];
                }
            }

            array_unshift($rows, $headerRow);
        }
    }
}

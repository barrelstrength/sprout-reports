<?php

namespace barrelstrength\sproutreports\integrations\sproutreports\datasources;

use barrelstrength\sproutbase\contracts\sproutreports\BaseDataSource;
use barrelstrength\sproutbase\models\sproutreports\Report as ReportModel;
use barrelstrength\sproutreports\SproutReports;
use Craft;
use craft\helpers\DateTimeHelper;

/**
 * Class SproutReportsTwigDataSource
 *
 * @package Craft
 */
class CustomTwigTemplate extends BaseDataSource
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
    public function getDefaultLabels(ReportModel $report, array $options = [])
    {
        if (!SproutReports::$app->twigDataSource->hasRun) {
            $this->processFrontEndResultsTemplate($report, $options);
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
    public function getResults(ReportModel $report, array $options = [])
    {
        if (!SproutReports::$app->twigDataSource->hasRun) {
            $this->processFrontEndResultsTemplate($report, $options);
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
    public function getOptionsHtml(array $options = [])
    {
        $optionErrors = $this->report->getErrors('options');
        $optionErrors = array_shift($optionErrors);

        // @todo - refactor? We pass $options to this method from the template, but the options
        // may already exist on the report.... maybe we can simplify?
        $options = count($options) ? array_merge($options, $this->report->getOptions()) : $this->report->getOptions();

        $customOptionsHtml = null;

        // If options template exists as setting, look for it on the front-end.
        // If not, return a nice message explain how to handle settings.
        if (isset($options['optionsTemplate']) && $options['optionsTemplate'] != '') {
            $customOptionsTemplatePath = Craft::$app->getPath()->getSiteTemplatesPath().'/'.$options['optionsTemplate'];

            $customOptionsFileContent = null;

            foreach (Craft::$app->getConfig()->getGeneral()->defaultTemplateExtensions as $extension) {
                if (file_exists($customOptionsTemplatePath.'.'.$extension)) {
                    $customOptionsFileContent = file_get_contents($customOptionsTemplatePath.'.'.$extension);
                    break;
                }
            }

            if (null !== $customOptionsFileContent) {
                // Add support for processing Template Options by including Craft CP Form Macros and
                // wrapping all option fields in the `options` namespace
                $customOptionsHtmlWithExtras = $customOptionsFileContent;

                $options = $this->prepOptions($options);

                $customOptionsHtml = Craft::$app->getView()->renderString($customOptionsHtmlWithExtras, [
                    'options' => count($options) ? $options : $this->report->getOptions(),
                    'errors' => $optionErrors
                ]);
            }
        }

        return Craft::$app->getView()->renderTemplate('sprout-reports/datasources/_options/twig', [
            'options' => $this->report->getOptions(),
            'errors' => $optionErrors,
            'optionContents' => $customOptionsHtml ?? null
        ]);
    }

    /**
     * @inheritdoc
     */
    public function validateOptions(array $options = [], array &$errors)
    {
        if (empty($options['resultsTemplate'])) {
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
     * @param ReportModel $report
     * @param array       $options
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function processFrontEndResultsTemplate(ReportModel $report, array $options = [])
    {
        $resultsTemplate = $report->getOption('resultsTemplate');

        $view = Craft::$app->getView();

        $view->setTemplateMode($view::TEMPLATE_MODE_SITE);

        $options = count($options) ? $options : $report->getOptions();
        $options = $this->prepOptions($options);

        // Process our front-end Results template which adds Labels and Rows to:
        // sproutReports()->reports->twigReportLabels;
        // sproutReports()->reports->twigReportRows;
        $view->renderTemplate($resultsTemplate, [
            'options' => $options
        ]);

        $view->setTemplateMode($view::TEMPLATE_MODE_CP);
    }

    public function processHeaderRow(&$rows)
    {
        $labels = SproutReports::$app->twigDataSource->labels;

        // If we don't have default labels, we will use the first row as for our column headers
        // We do so by making the first row the keys of the second row
        if (empty($labels) && count($rows)) {
            $headerRow = [];
            $firstRow = array_shift($rows);

            if (count($firstRow)) {
                $secondRow = array_shift($rows);

                foreach ($firstRow as $key => $attribute) {
                    $headerRow[$attribute] = $secondRow[$key];
                }
            }

            array_unshift($rows, $headerRow);
        }
    }

    /**
     * @param array $options
     *
     * @return array|null
     */
    public function prepOptions(array $options)
    {
        foreach ($options as $name => $option) {
            $datetime = strpos($name, 'datetime');

            // Date time field
            if ($datetime === 0) {
                $value = DateTimeHelper::toDateTime($options[$name]);
                $options[$name] = $value;
            }
        }

        return $options;
    }
}

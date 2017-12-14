<?php

namespace Craft;

/**
 * Class SproutReportsTwigDataSource
 *
 * @package Craft
 */
class SproutReportsTwigDataSource extends SproutReportsBaseDataSource
{
	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return Craft::t('Custom Twig Template');
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return Craft::t('Create a report using Twig in your front-end templates folder.');
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
	public function getDefaultLabels($report, $options = array())
	{
		if (!sproutReports()->twigDataSource->hasRun)
		{
			$this->processFrontEndResultsTemplate($report, $options);
			sproutReports()->twigDataSource->hasRun = true;
		}

		$labels = sproutReports()->twigDataSource->labels;

		if (count($labels))
		{
			return $labels;
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function getResults(SproutReports_ReportModel &$report, $options = array())
	{
		if (!sproutReports()->twigDataSource->hasRun)
		{
			$this->processFrontEndResultsTemplate($report, $options);
			sproutReports()->twigDataSource->hasRun = true;
		}

		$rows = sproutReports()->twigDataSource->rows;

		$this->processHeaderRow($rows);

		if (count($rows))
		{
			return $rows;
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsHtml(array $options = array())
	{
		$optionErrors        = $this->report->getErrors('options');
		$optionErrors        = array_shift($optionErrors);

		// @todo - refactor? We pass $options to this method from the template, but the options
		// may already exist on the report.... maybe we can simplify?
		$options = count($options) ? array_merge($options, $this->report->getOptions()) : $this->report->getOptions();

		// If options template exists as setting, look for it on the front-end.
		// If not, return a nice message explain how to handle options.
		$customOptionsHtml = null;

		if (isset($options['optionsTemplate']) && $options['optionsTemplate'] != '')
		{
			$customOptionsTemplatePath = craft()->path->getSiteTemplatesPath() . $options['optionsTemplate'];

			$customOptionsFileContent = null;

			foreach (craft()->config->get('defaultTemplateExtensions') as $extension)
			{
				if (IOHelper::fileExists($customOptionsTemplatePath . '.' . $extension))
				{
					$customOptionsFileContent = IOHelper::getFileContents($customOptionsTemplatePath . '.' . $extension);
					break;
				}
			}

			// Add support for processing Template Options by including Craft CP Form Macros and
			// wrapping all option fields in the `options` namespace
			$customOptionsHtmlWithExtras = $customOptionsFileContent;

			$options = $this->prepOptions($options);

			$customOptionsHtml = craft()->templates->renderString($customOptionsHtmlWithExtras, array(
				'options' => count($options) ? $options : $this->report->getOptions(),
				'errors'  => $optionErrors
			));
		}

		return craft()->templates->render('sproutreports/datasources/_options/twig', array(
			'options'        => $this->report->getOptions(),
			'errors'         => $optionErrors,
			'optionContents' => isset($customOptionsHtml) ? $customOptionsHtml : null
		));
	}

	/**
	 * @inheritdoc
	 */
	public function validateOptions(array $options = array(), array &$errors = array())
	{
		if (empty($options['resultsTemplate']))
		{
			$errors['resultsTemplate'][] = Craft::t('Results template cannot be blank.');
		}

		if (count($errors))
		{
			return false;
		}

		return true;
	}

	/**
	 * Make sure we only process our template once.
	 * Since we need data from the template in both the getDefaultLabels and getResults
	 * methods we have to check in both places
	 *
	 * @param SproutReports_ReportModel $report
	 * @param array                     $options
	 */
	public function processFrontEndResultsTemplate(SproutReports_ReportModel $report, $options = array())
	{
		$resultsTemplate = $report->getOption('resultsTemplate');

		craft()->templates->setTemplateMode(TemplateMode::Site);

		craft()->templates->render($resultsTemplate, array(
			'options' => count($options) ? $options : $report->getOptions()
		));

		craft()->templates->setTemplateMode(TemplateMode::CP);
	}

	public function processHeaderRow(&$rows)
	{
		$labels = sproutReports()->twigDataSource->labels;

		// If we don't have default labels, we will use the first row as for our column headers
		// We do so by making the first row the keys of the second row
		if (empty($labels) && count($rows))
		{
			$headerRow = array();
			$firstRow  = array_shift($rows);

			if (count($firstRow))
			{
				$secondRow = array_shift($rows);

				foreach ($firstRow as $key => $attribute)
				{
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
		foreach ($options as $name => $option)
		{
			$datetime = strpos($name, 'datetime');

			// Date time field
			if ($datetime === 0)
			{
				$value          = DateTime::createFromString($options[$name], craft()->timezone);
				$options[$name] = $value;
			}
		}

		return $options;
	}
}

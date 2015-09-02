<?php
namespace Craft;

class SproutReports_SingleNumberWidget extends BaseWidget
{
	protected $colspan = 4;

	/**
	 * Returns the type of widget this is.
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Single Number Report');
	}

	/**
	 * Gets the widget's title.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Craft::t($this->getSettings()->heading);
	}


	/**
	 * Gets the widget's body HTML.
	 *
	 * @return string
	 */
	public function getBodyHtml()
	{
		$result		= null;
		$reportId	= $this->getSettings()->getAttribute('reportId');
		$report		= craft()->sproutReports_reports->getReportById($reportId);

		if ($report && !empty($report['returnsSingleNumber']))
		{
			$result = craft()->sproutReports_reports->runReport($report['customQuery'], $report);

			if ($result)
			{
				$result = array_shift($result->read());
			}
		}

		return craft()->templates->render('sproutreports/_widgets/singlenumber/index', array(
			'settings'	=> $this->getSettings(),
			'result'	=> $result
		));
	}

	protected function defineSettings()
	{
		return array(
			'heading'		=> array(AttributeType::String, 'required' => true),
			'reportId'		=> array(AttributeType::Number),
			'description'	=> array(AttributeType::String),
			'resultPrefix'	=> array(AttributeType::String),
		);
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render('sproutreports/_widgets/singlenumber/settings', array(
			'settings'				=> $this->getSettings(),
			'singleNumberReports'	=> $this->getSingleNumberReportAsOptions()
		));
	}

	public function getSingleNumberReportAsOptions()
	{
		$options = array();
		$reports = craft()->sproutReports_reports->getAllReportsByAttributes(array('returnsSingleNumber' => 1));

		if ($reports)
		{
			foreach ($reports as $report)
			{
				$options[$report->id] = $report->name;
			}
		}

		return $options;
	}
}

<?php
namespace Craft;

class SproutReports_NumberReportWidget extends BaseWidget
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Number Report');
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return Craft::t($this->getSettings()->heading);
	}

	/**
	 * @return string
	 */
	public function getBodyHtml()
	{
		$report = sproutReports()->reports->getReport($this->getSettings()->reportId);

		if ($report)
		{
			$dataSource = sproutReports()->dataSources->getDataSourceById($report->dataSourceId);

			if ($dataSource)
			{
				$result = $dataSource->getResults($report);

				return craft()->templates->render(
					'sproutreports/_widgets/number/index',
					array(
						'settings' => $this->getSettings(),
						'result'   => $this->getScalarValue($result)
					)
				);
			}
		}
	}

	protected function defineSettings()
	{
		return array(
			'heading'      => array(AttributeType::String, 'required' => true),
			'description'  => array(AttributeType::String),
			'resultPrefix' => array(AttributeType::String),
			'reportId'     => array(AttributeType::Number),
		);
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render(
			'sproutreports/_widgets/number/settings',
			array(
				'settings' => $this->getSettings()
			)
		);
	}

	protected function getScalarValue($result)
	{
		$value = null;

		if (is_array($result))
		{
			if (count($result) == 1 && count($result[0]) == 1)
			{
				$value = array_shift($result[0]);
			}
			else
			{
				$value = count($result);
			}
		}
		else
		{
			$value = $result;
		}

		return $value;
	}
}

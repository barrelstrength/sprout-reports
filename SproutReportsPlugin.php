<?php
namespace Craft;

class SproutReportsPlugin extends BasePlugin
{
	function init()
	{
		parent::init();
		require CRAFT_PLUGINS_PATH.'sproutreports/vendor/autoload.php';
		craft()->on('plugins.loadPlugins', array($this, 'onLoadPlugins'));
	}

	/**
	 * Returns the official name or an override if one is set
	 *
	 * @return	string
	 */
	function getName()
	{
		$pluginName			= Craft::t('Sprout Reports');
		$pluginNameOverride	= $this->getSettings()->pluginNameOverride;

		if ($pluginNameOverride)
		{
			return $pluginNameOverride;
		}

		return $pluginName;
	}

	function getVersion()
	{
		return '0.4.6';
	}

	function getDeveloper()
	{
		return 'Barrel Strength Design';
	}

	function getDeveloperUrl()
	{
		return 'http://barrelstrengthdesign.com';
	}

	public function hasCpSection()
	{
		return true;
	}

	protected function defineSettings()
	{
		return array(
			'pluginNameOverride'	=> AttributeType::String,
		);
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render(
			'sproutreports/_settings/plugin',
				array(
				'settings'	=> $this->getSettings()
			)
		);
	}

	public function registerUserPermissions()
	{
		return array(
			'editReports'	=> array('label' => Craft::t('Edit Reports')),
			'singleNumberReports'	=> array('label' => Craft::t('Single Number Reports')),
		);
	}

	public function registerCpRoutes()
	{
		return array(

			// Adjust output to account for selected group
			'sproutreports/(?P<groupId>\d+)' =>
			'sproutreports',

			'sproutreports/reports/(?P<newReport>new)' => 
			'sproutreports/reports/_edit',

			'sproutreports/reports/edit/(?P<reportId>\d+)' => 
			'sproutreports/reports/_edit',

			'sproutreports/results/(?P<reportId>\d+)' => array(
				'action' => 'sproutReports/reports/results'
			)
		);
	}

    public function onLoadPlugins()
    {
        $this->registerReports();
    }

    /**
     * @param SproutReportsSproutFormsIntegration $hookReport
     * @return SproutReports_ReportModel
     */
    protected function convertHookReportToNative($hookReport)
    {
        $group = new SproutReports_ReportGroupModel;
        $group->name = 'Sprout Forms';
        craft()->sproutReports_reports->saveGroup($group);
        $group = SproutReports_ReportGroupRecord::model()->findByAttributes(array('name' => $group->name));

        $report = new SproutReports_ReportModel;
        $report->name = $hookReport->getName();
        $report->groupId = $group->id;
        $report->handle = 'report_' . preg_replace('/^a-zA-Z0-9/', '', $hookReport->getName()); //need to care about valid handle
        $report->customQuery = $hookReport->getQuery();
        $report->description = $hookReport->getDescription();
        $report->settings = $hookReport->getUserOptions();
        $report->customQueryEditable =  $hookReport->getIsCustomQueryEditable();

        craft()->sproutReports_reports->saveReport($report);
        return $report;
    }

    /*
     * Register 3rd party reports
     * @return void
     */
    protected function registerReports()
    {
        $reports = craft()->plugins->call('registerSproutReports');
        foreach ($reports as $report)
        {
            if (!is_array($report))
            {
                $report = array($report);
            }
            foreach ($report as $singleReport)
            {
                $this->convertHookReportToNative($singleReport);
            }
        }
    }
}
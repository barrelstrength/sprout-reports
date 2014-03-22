<?php
namespace Craft;

class SproutReportsPlugin extends BasePlugin
{
	function init()
	{
		require CRAFT_PLUGINS_PATH.'sproutreports/vendor/autoload.php';
	}

	/**
	 * Returns the official name or an override if one is set
	 *
	 * @return	string
	 */
	function getName()
	{
		$pluginName			= Craft::t('Sprout Reports');
		$pluginNameOverride	= $this->getSettings()->getAttribute('pluginNameOverride');

		if ($pluginNameOverride)
		{
			return $pluginNameOverride;
		}

		return $pluginName;
	}

	function getVersion()
	{
		return '0.4.1';
	}

	function getDeveloper()
	{
		return 'Barrel Strength Design';
	}

	function getDeveloperUrl()
	{
		return 'http://straightupcraft.com';
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
		);
	}

	public function registerCpRoutes()
	{
		return array(
			'sproutreports/reports/(?P<newReport>new)' => 
			'sproutreports/reports/_edit',

			'sproutreports/reports/edit/(?P<reportId>\d+)' => 
			'sproutreports/reports/_edit',

			'sproutreports/results/(?P<reportId>\d+)' => 
			'sproutreports/results/index',
		);
	}

}

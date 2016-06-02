<?php
namespace Craft;

/**
 * Class SproutReportsPlugin
 *
 * @package Craft
 */
class SproutReportsPlugin extends BasePlugin
{
	/**
	 * Defines secondary services a properties for improved public API
	 *
	 * @throws \Exception
	 */
	public function init()
	{
		parent::init();

		Craft::import('plugins.sproutreports.contracts.*');
		Craft::import('plugins.sproutreports.integrations.sproutreports.reports.*');
		Craft::import('plugins.sproutreports.integrations.sproutreports.datasources.*');

		if (craft()->request->isCpRequest() && craft()->request->getSegment(1) == 'sproutreports')
		{
			// @todo Craft 3 - update to use info from config.json
			craft()->templates->includeJsResource('sproutreports/js/brand.js');
			craft()->templates->includeJs("
				sproutFormsBrand = new Craft.SproutBrand();
				sproutFormsBrand.displayFooter({
					pluginName: 'Sprout Reports',
					pluginUrl: 'http://sprout.barrelstrengthdesign.com/craft-plugins/reports',
					pluginVersion: '" . $this->getVersion() . "',
					pluginDescription: '" . $this->getDescription() . "',
					developerName: '(Barrel Strength)',
					developerUrl: '" . $this->getDeveloperUrl() . "'
				});
			");
		}
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		$pluginName         = Craft::t('Sprout Reports');
		$pluginNameOverride = $this->getSettings()->pluginNameOverride;

		return ($pluginNameOverride) ? $pluginNameOverride : $pluginName;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return 'Powerful custom reports.';
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return '0.8.4';
	}

	/**
	 * @return string
	 */
	public function getSchemaVersion()
	{
		return '0.8.0';
	}

	/**
	 * @return string
	 */
	public function getDeveloper()
	{
		return 'Barrel Strength Design';
	}

	/**
	 * @return string
	 */
	public function getDeveloperUrl()
	{
		return 'http://barrelstrengthdesign.com';
	}

	/**
	 * @return string
	 */
	public function getDocumentationUrl()
	{
		return "http://sprout.barrelstrengthdesign.com/craft-plugins/results/docs";
	}

	/**
	 * @return string
	 */
	public function getReleaseFeedUrl()
	{
		return 'https://sprout.barrelstrengthdesign.com/craft-plugins/reports/releases.json';
	}

	/**
	 * @return bool
	 */
	public function hasCpSection()
	{
		return true;
	}

	/**
	 * @return array
	 */
	protected function defineSettings()
	{
		return array(
			'pluginNameOverride' => AttributeType::String,
		);
	}

	/**
	 * @return string
	 */
	public function getSettingsUrl()
	{
		return 'sproutreports/settings';
	}

	/**
	 * @return array
	 */
	public function registerUserPermissions()
	{
		return array(
			'sproutReports-editReports'  => array('label' => Craft::t('Edit Reports')),
			'sproutReports-editSettings' => array(
				'label' => Craft::t('Edit Plugin Settings')
			)
		);
	}

	/**
	 * @throws \Exception
	 */
	public function onAfterInstall()
	{
		$this->init();

		$isCraftPro = craft()->getEdition() == Craft::Pro ? true : false;

		Craft::import('plugins.sproutreports.integrations.sproutreports.reports.SproutReportsCategoriesReport');

		if ($isCraftPro)
		{
			Craft::import('plugins.sproutreports.integrations.sproutreports.reports.SproutReportsUsersReport');
		}

		$defaultGroup = sproutReports()->reportGroups->createGroupByName('Sprout Reports');

		if (craft()->plugins->getPlugin('sproutreports'))
		{
			sproutReports()->reports->registerReports(new SproutReportsUsersReport(), $defaultGroup);
		}
	}

	/**
	 * @return array
	 */
	public function registerSproutReportsDataSources()
	{
		$isCraftPro = craft()->getEdition() == Craft::Pro ? true : false;

		$sources =  array(
			new SproutReportsCategoriesDataSource(),
			new SproutReportsQueryDataSource(),
		);

		if ($isCraftPro)
		{
			$sources[] = new SproutReportsUsersDataSource();
		}

		return $sources;
	}

	public function registerCpRoutes()
	{
		return array(
			'sproutreports' =>
				'sproutreports/reports/index',

			'sproutreports/reports/(?P<groupId>\d+)' =>
				'sproutreports/reports/index',

			'sproutreports/reports/(?P<plugin>{handle})/(?P<dataSourceKey>{handle})/new' =>
				array('action' => 'sproutReports/reports/editReport'),

			'sproutreports/reports/(?P<plugin>{handle})/(?P<dataSourceKey>{handle})/edit/(?P<reportId>\d+)' =>
				array('action' => 'sproutReports/reports/editReport'),

			'sproutreports/reports/view/(?P<reportId>\d+)' =>
				array('action' => 'sproutReports/reports/resultsIndex'),

			'sproutreports/settings' =>
				array('action' => 'sproutReports/settings/settingsIndexTemplate'),
		);
	}
}

/**
 * @return SproutReportsService
 */
function sproutReports()
{
	return Craft::app()->getComponent('sproutReports');
}

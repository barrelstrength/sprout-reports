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
		$override = trim($this->getSettings()->getAttribute('pluginNameOverride'));

		return empty($override) ? Craft::t('Sprout Reports') : $override;
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
	public function getSettingsHtml()
	{
		return craft()->templates->render(
			'sproutreports/_cp/settings',
			array(
				'settings' => $this->getSettings()
			)
		);
	}

	/**
	 * @return array
	 */
	public function registerUserPermissions()
	{
		return array(
			'editReports' => array('label' => Craft::t('Edit Reports')),
		);
	}

	/**
	 * @throws \Exception
	 */
	public function onAfterInstall()
	{
		$this->init();

		Craft::import('plugins.sproutreports.integrations.sproutreports.reports.SproutReportsCategoriesReport');
		Craft::import('plugins.sproutreports.integrations.sproutreports.reports.SproutReportsUsersReport');

		sproutReports()->groups->getOrCreateByName('Sprout Reports');

		if (craft()->plugins->getPlugin('sproutreports'))
		{
			sproutReports()->reports->register(new SproutReportsCategoriesReport());
			sproutReports()->reports->register(new SproutReportsUsersReport());
		}
	}

	/**
	 * @return array
	 */
	public function registerSproutReportsDataSources()
	{
		return array(
			new SproutReportsCategoriesDataSource(),
			new SproutReportsUsersDataSource(),
			new SproutReportsQueryDataSource(),
		);
	}

	public function registerCpRoutes()
	{
		return array(
			'sproutreports' =>
			'sproutreports/index',

			'sproutreports/(?P<groupId>\d+)' =>
			'sproutreports/index',

			'sproutreports/reports/(?P<plugin>{handle})/(?P<dataSourceKey>{handle})/edit/new' =>
				array('action' => 'sproutReports/editReport'),

			'sproutreports/reports/(?P<plugin>{handle})/(?P<dataSourceKey>{handle})/edit/(?P<reportId>\d+)' =>
				array('action' => 'sproutReports/editReport'),

			'sproutreports/reports/view/(?P<reportId>\d+)' =>
				array('action' => 'sproutReports/runReport'),
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

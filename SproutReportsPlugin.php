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
	public function getVersion()
	{
		return '0.6.0';
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
			'sproutreports/_settings/plugin',
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

		Craft::import('plugins.sproutreports.integrations.sproutreports.reports.SproutReportsUsersReport');

		sproutReports()->reports->register(new SproutReportsUsersReport());
	}

	/**
	 * @return array
	 */
	public function registerSproutReportsDataSources()
	{
		return array(
			new SproutReportsUsersDataSource(),
			new SproutReportsQueryDataSource(),
		);
	}

	public function registerCpRoutes()
	{
		return array(
			'sproutreports'                                                                              =>
				'sproutreports/index',
			'sproutreports/reports/(?P<plugin>{handle})/(?P<dataSourceKey>{handle})/edit/new'               =>
				array('action' => 'sproutReports/editReport'),
			'sproutreports/reports/(?P<plugin>{handle})/(?P<dataSourceKey>{handle})/edit/(?P<reportId>\d+)' =>
				array('action' => 'sproutReports/editReport'),
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

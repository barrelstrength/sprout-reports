<?php
namespace Craft;

class SproutReports_MapWidget extends BaseWidget
{
	protected $colspan = 4;

	/**
	 * Returns the type of widget this is.
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Map Report');
	}

	/**
	 * Gets the widget's title.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Craft::t('People on a map!');
	}


	/**
	 * Gets the widget's body HTML.
	 *
	 * @return string
	 */
	public function getBodyHtml()
	{
		craft()->templates->includeJsFile('http://maps.google.com/maps/api/js?sensor=false', true);

		return craft()->templates->render('sproutreports/_widgets/map/index', array(
			'settings'	=> $this->getSettings(),
		));
	}

}

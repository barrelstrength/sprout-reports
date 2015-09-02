<?php
namespace Craft;

class SproutReports_BarWidget extends BaseWidget
{
	protected $colspan = 1;

	/**
	 * Returns the type of widget this is.
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Bar Report');
	}

	/**
	 * Gets the widget's title.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Craft::t('Bar bar bar!');
	}


	/**
	 * Gets the widget's body HTML.
	 *
	 * @return string
	 */
	public function getBodyHtml()
	{
		return craft()->templates->render('sproutreports/_widgets/bar/index', array(
			'settings'	=> $this->getSettings(),
		));
	}

}

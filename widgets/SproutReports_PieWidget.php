<?php
namespace Craft;

class SproutReports_PieWidget extends BaseWidget
{
	protected $colspan = 1;

	/**
	 * Returns the type of widget this is.
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Pie Report');
	}

	/**
	 * Gets the widget's title.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Craft::t('Eat Pie!');
	}


	/**
	 * Gets the widget's body HTML.
	 *
	 * @return string
	 */
	public function getBodyHtml()
	{
		// {% includeJsResource "sproutreports/js/d3/d3.min.js" %}
		// {% includeJsResource "sproutreports/js/d3/nv.d3.min.js" %}
		craft()->templates->includeJsResource('sproutreports/js/d3/nv.d3.min.js', true);
		craft()->templates->includeJsResource('sproutreports/js/d3/d3.min.js', true); 
		
		craft()->templates->includeJsResource('sproutreports/js/custom.js');

		return craft()->templates->render('sproutreports/_widgets/pie/index', array(
			'settings'	=> $this->getSettings(),
		));
	}

}

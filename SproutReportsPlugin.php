<?php
namespace Craft;

class SproutReportsPlugin extends BasePlugin
{
  function init()
  {
    require CRAFT_PLUGINS_PATH.'sproutreports/vendor/autoload.php';
  }

  function getName()
  {
    $pluginName = Craft::t('Sprout Reports');

    // The plugin name override
    $plugin = craft()->db->createCommand()
                         ->select('settings')
                         ->from('plugins')
                         ->where('class=:class', array(':class'=> 'SproutReports'))
                         ->queryScalar();
    
    $plugin = json_decode( $plugin, true );
    $pluginNameOverride = $plugin['pluginNameOverride'];         

    return ($pluginNameOverride) ? $pluginNameOverride : $pluginName;
  }

  function getVersion()
  {
    return '0.4.0';
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

  /**
   * Define plugin settings
   * 
   * @return array
   */
  protected function defineSettings()
  {
      return array(
          'pluginNameOverride'      => AttributeType::String,
      );
  }

  /**
   * Return plugin settings form
   * 
   * @return string
   */
  public function getSettingsHtml()
  {
      return craft()->templates->render('sproutreports/_settings/plugin', array(
          'settings' => $this->getSettings()
      ));
  }

  public function registerUserPermissions()
  {
    return array(
      'editReports' => array('label' => Craft::t('Edit Reports')),
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

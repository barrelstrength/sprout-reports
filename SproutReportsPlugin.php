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
    return Craft::t('Sprout Reports');
  }

  function getVersion()
  {
    return '0.3.1';
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

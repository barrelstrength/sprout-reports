<?php
namespace Craft;

class CraftDiscoverPlugin extends BasePlugin
{
    function init()
    {
        require 'vendor/autoload.php';
    }

    function getName()
    {
        return Craft::t('CraftDiscover');
    }

    function getVersion()
    {
        return '1.0';
    }

    function getDeveloper()
    {
        return 'StraightUpCraft';
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
            'asetting'   => array(AttributeType::String, 'required' => true, 'label' => 'A Setting'),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('craftdiscover/settings', array(
            'settings' => $this->getSettings()
        ));
    }


    public function hookRegisterCpRoutes()
    {
        return array(
            'craftdiscover\/query' => 'craftdiscover/reports/query',
        );
    }

}

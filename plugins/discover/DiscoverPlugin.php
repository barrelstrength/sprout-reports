<?php
namespace Craft;

class DiscoverPlugin extends BasePlugin
{
    function init()
    {
        require CRAFT_PLUGINS_PATH.'discover/vendor/autoload.php';
    }

    function getName()
    {
        return Craft::t('Discover');
    }

    function getVersion()
    {
        return '1.0';
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
            'asetting'   => array(AttributeType::String, 'required' => true, 'label' => 'A Setting'),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('discover/settings', array(
            'settings' => $this->getSettings()
        ));
    }


    public function hookRegisterCpRoutes()
    {
        return array(
            'discover\/query' => 'discover/reports/query',
        );
    }

}

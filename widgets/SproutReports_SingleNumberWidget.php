<?php
namespace Craft;

class SproutReports_SingleNumberWidget extends BaseWidget
{

    /**
     * Returns the type of widget this is.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Single Number Report');
    }

    /**
     * Gets the widget's title.
     *
     * @return string
     */
    public function getTitle()
    {
        return Craft::t($this->getSettings()->heading);
    }


    /**
     * Gets the widget's body HTML.
     *
     * @return string
     */
    public function getBodyHtml()
    {   
        $queryString = $this->getSettings()->query;

        $command = craft()->db->createCommand($queryString);
        $result = $command->queryScalar();
        
        return craft()->templates->render('sproutreports/_widgets/singlenumber/index', array(
            'settings' => $this->getSettings(),
            'result' => $result
        ));
    }


    protected function defineSettings()
    {
        return array(
            'heading' => array(AttributeType::String, 'required' => true),
            'query' => array(AttributeType::Mixed, 'required' => true),
            'description' => array(AttributeType::String),
            'resultPrefix' => array(AttributeType::String),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('sproutreports/_widgets/singlenumber/settings', array(
            'settings' => $this->getSettings()
        ));
    }
}

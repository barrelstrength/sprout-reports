<?php
namespace Craft;

/**
 * Class SproutReports_DataSourceModel
 *
 * @package Craft
 * @property string $dataSourceId
 * @property array $options
 * @property int $status
 */
class SproutReports_DataSourceModel extends BaseModel
{
    /**
     * @return array
     */
	protected function defineAttributes()
	{
        $defaults = parent::defineAttributes();

        $attributes = array(
            'id'           => array(AttributeType::Number),
            'dataSourceId' => array(AttributeType::String, 'required' => true),
            'options'      => array(AttributeType::Mixed, 'required' => false),
            'status'       => array(AttributeType::Bool, 'default' => true)
        );

        return array_merge($defaults, $attributes);
	}
}
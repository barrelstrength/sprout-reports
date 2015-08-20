<?php
namespace Craft;

class SproutReports_ReportRecord extends BaseRecord
{
	public function getTableName()
	{
		return 'sproutreports_reports';
	}

	protected function defineAttributes()
	{
		return array(
			'groupId'				=> array(AttributeType::Number),
			'name'					=> array(AttributeType::String, 'required' => true),
			'handle'				=> array(AttributeType::String, 'required' => true),
			'description'			=> AttributeType::String,
			'customQuery'			=> AttributeType::Mixed,
			'settings'        => AttributeType::Mixed,
			'returnsSingleNumber'	=> array(AttributeType::Bool, 'default' => false),
			'customQueryEditable'	=> array(AttributeType::Bool, 'default' => true),
			'isEmailList'	=> array(AttributeType::Bool, 'default' => false)
		);
	}

	public function defineIndexes()
	{
		return array(
			array('columns' => array('name', 'handle'), 'unique' => true),
		);
	}

	public function create()
	{
		$class	= get_class($this);
		$record	= new $class();

		return $record;
	}

    public function beforeSave()
    {
        if (empty($this->settings))
        {
            $this->settings = array();
        }
        $this->settings = JsonHelper::encode($this->settings);
        parent::beforeSave();
        return true;
    }

    public function afterFind()
    {
        $this->settings = JsonHelper::decode($this->settings);
        parent::afterFind();
        return true;
    }

}

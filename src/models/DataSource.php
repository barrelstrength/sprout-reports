<?php
namespace barrelstrength\sproutreports\models;

use craft\base\Model;

class DataSource extends Model
{
	/**
	 * @return array
	 */
	public function safeAttributes()
	{
		 return ['id', 'dataSourceId', 'options' , 'allowNew'];
	}
}
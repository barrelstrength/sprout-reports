<?php
namespace barrelstrength\sproutreports\models;

use craft\base\Model;
use barrelstrength\sproutreports\records\Report as ReportRecord;
use craft\validators\HandleValidator;
use craft\validators\UniqueValidator;

class ReportGroup extends Model
{
	public $id;
	public $name;
	public $handle;

}
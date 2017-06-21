<?php
namespace barrelstrength\sproutreports\services;

use barrelstrength\sproutcore\integrations\sproutreports\contracts\BaseDataSource;
use Craft;
use barrelstrength\sproutreports\models\DataSource as DataSourceModel;
use barrelstrength\sproutreports\records\DataSource as DataSourceRecord;
use yii\base\Component;
use craft\events\RegisterComponentTypesEvent;

/**
 * Class DataSources
 *
 * @package Craft
 */
class DataSources  extends Component
{
	/**
	 * Save attributes to datasources record table
	 *
	 * @param DataSourceModel $model
	 *
	 * @return bool
	 */
  public function saveDataSource(DataSourceModel $model)
  {
      $result = false;

      $record = DataSourceRecord::find([
          'dataSourceId' => $model->dataSourceId
      ])->one();

      if ($record == null)
      {
          $record = new DataSourceRecord();
      }

      $attributes = $model->getAttributes();

      if (!empty($attributes))
      {
          foreach ($attributes as $handle => $value)
          {
              // Ignore id for dataSourceId
              if ($handle == 'id') continue;

              $record->setAttribute($handle, $value);
          }
      }

	    $db = Craft::$app->getDb();
	    $transaction = $db->beginTransaction();

      if ($record->validate())
      {
          if ($record->save(false))
          {
              $model->id = $record->id;

              if ($transaction)
              {
                  $transaction->commit();
              }

              $result = true;
          }
      }
      else
      {
          $model->addErrors($record->getErrors());
      }

      return $result;
  }
}

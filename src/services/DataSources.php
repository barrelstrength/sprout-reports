<?php
namespace barrelstrength\sproutreports\services;

use yii\base\Component;
use craft\events\RegisterComponentTypesEvent;
use barrelstrength\sproutreports\contracts\BaseDataSource;

/**
 * Class DataSources
 *
 * @package Craft
 */
class DataSources  extends Component
{

	const EVENT_REGISTER_DATA_SOURCES = "registerSproutReportsDataSources";
	/**
	 * @var BaseDataSource[]
	 */
	protected $dataSources;

	/**
	 *
	 * @param string $id
	 *
	 * @throws Exception
	 * @return BaseDataSource
	 */
	public function getDataSourceById($id)
	{
		$sources = $this->getAllDataSources();

		if (isset($sources[$id]))
		{
			return $sources[$id];
		}

		throw new Exception(\Craft::t('Could not find data source with id {id}.', compact('id')));
	}

	/**
	 * @return null|BaseDataSource[]
	 */
	public function getAllDataSources()
	{
		if (is_null($this->dataSources))
		{
			$event = new RegisterComponentTypesEvent([
				'types' => []
			]);

			$this->trigger(self::EVENT_REGISTER_DATA_SOURCES, $event);

			$responses = $event->types;

			$names = array();

			if ($responses)
			{
				/**
				 * @var BaseDataSource $dataSource
				 */
				foreach ($responses as $dataSource)
				{

					if ($dataSource && $dataSource instanceof BaseDataSource)
					{
						//$dataSource->setPluginName(\Craft::$app->getPlugin($plugin)->getName());
					//	$dataSource->setPluginHandle($plugin);

						$this->dataSources[$dataSource->getId()] = $dataSource;

						$names[] = $dataSource->getName();
					}
				}

				// Sort data sources by name
				$this->_sortDataSources($names, $this->dataSources);
			}
		}

		return $this->dataSources;
	}

	///**
	// * @param string $pluginHandle
	// * @param string $dataSourceClass
	// *
	// * @return string
	// * @throws Exception
	// */
	//public function generateId($pluginHandle, $dataSourceClass)
	//{
	//	$pluginHandle    = strtolower($pluginHandle);
	//	$dataSourceClass = strtolower($dataSourceClass);
	//	$dataSourceClass = str_replace($pluginHandle, '', $dataSourceClass);
	//	$dataSourceClass = str_replace('datasource', '', $dataSourceClass);
	//
	//	return sprintf('%s.%s', $pluginHandle, $dataSourceClass);
	//}
	//
	///**
	// * @param $names
	// * @param $secondaryArray
	// *
	// * @return null
	// */
	private function _sortDataSources(&$names, &$secondaryArray)
	{
		// TODO: Remove this check for Craft 3.
		if (PHP_VERSION_ID < 50400)
		{
			// Sort plugins by name
			array_multisort($names, $secondaryArray);
		}
		else
		{

			// Sort plugins by name
			array_multisort($names, SORT_NATURAL | SORT_FLAG_CASE, $secondaryArray);
		}
	}
	//
   // public function saveDataSource(SproutReports_DataSourceModel $model)
   // {
   //     $result = false;
	//
   //     $record = SproutReports_DataSourceRecord::model()->findByAttributes(array(
   //         'dataSourceId' => $model->dataSourceId
   //     ));
	//
   //     if ($record == null)
   //     {
   //         $record = new SproutReports_DataSourceRecord();
   //     }
	//
   //     $attributes = $model->getAttributes();
	//
   //     if (!empty($attributes))
   //     {
   //         foreach ($attributes as $handle => $value)
   //         {
   //             // Ignore id for dataSourceId
   //             if ($handle == 'id') continue;
	//
   //             $record->setAttribute($handle, $value);
   //         }
   //     }
	//
   //     $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
	//
   //     if ($record->validate())
   //     {
   //         if ($record->save(false))
   //         {
   //             $model->id = $record->id;
	//
   //             if ($transaction && $transaction->active)
   //             {
   //                 $transaction->commit();
   //             }
	//
   //             $result = true;
   //         }
   //     }
   //     else
   //     {
   //         $model->addErrors($record->getErrors());
   //     }
	//
   //     return $result;
   // }
}

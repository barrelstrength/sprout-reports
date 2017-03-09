<?php
namespace barrelstrength\sproutreports\services;

use Craft;
use barrelstrength\sproutreports\models\DataSource as DataSourceModel;
use barrelstrength\sproutreports\records\DataSource as DataSourceRecord;
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
	 * @throws \Exception
	 * @return BaseDataSource
	 */
	public function getDataSourceById($id)
	{
		$sources = $this->getAllDataSources();

		if (isset($sources[$id]))
		{
			return $sources[$id];
		}

		throw new \Exception(\Craft::t('Could not find data source with id {id}.', compact('id')));
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

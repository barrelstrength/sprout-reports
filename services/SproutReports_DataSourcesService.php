<?php
namespace Craft;

/**
 * Class SproutReports_DataSourcesService
 *
 * @package Craft
 */
class SproutReports_DataSourcesService extends BaseApplicationComponent
{
	/**
	 * @var SproutReportsBaseDataSource[]
	 */
	protected $dataSources;

	/**
	 *
	 * @param string $id
	 *
	 * @throws Exception
	 * @return SproutReportsBaseDataSource
	 */
	public function getDataSourceById($id)
	{
		$sources = $this->getAllDataSources();

		if (isset($sources[$id]))
		{
			return $sources[$id];
		}

		throw new Exception(Craft::t('Could not find data source with id {id}.', compact('id')));
	}

	/**
	 * @return null|SproutReportsBaseDataSource[]
	 */
	public function getAllDataSources()
	{
		if (is_null($this->dataSources))
		{
			$responses = craft()->plugins->call('registerSproutReportsDataSources');

			$names = array();

			if ($responses)
			{
				foreach ($responses as $plugin => $dataSources)
				{
					/**
					 * @var SproutReportsBaseDataSource $dataSource
					 */
					foreach ($dataSources as $dataSource)
					{
						if ($dataSource && $dataSource instanceof SproutReportsBaseDataSource)
						{
							$dataSource->setId($plugin);
							$dataSource->setPluginName(craft()->plugins->getPlugin($plugin)->getName());
							$dataSource->setPluginHandle($plugin);

							$this->dataSources[$dataSource->getId()] = $dataSource;

							$names[] = $dataSource->getName();
						}
					}
				}

				// Sort data sources by name
				$this->_sortDataSources($names, $this->dataSources);
			}
		}

		return $this->dataSources;
	}

	/**
	 * @param string $pluginHandle
	 * @param string $dataSourceClass
	 *
	 * @return string
	 * @throws Exception
	 */
	public function generateId($pluginHandle, $dataSourceClass)
	{
		$pluginHandle    = strtolower($pluginHandle);
		$dataSourceClass = strtolower($dataSourceClass);
		$dataSourceClass = str_replace($pluginHandle, '', $dataSourceClass);
		$dataSourceClass = str_replace('datasource', '', $dataSourceClass);

		return sprintf('%s.%s', $pluginHandle, $dataSourceClass);
	}

	/**
	 * @param $names
	 * @param $secondaryArray
	 *
	 * @return null
	 */
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
}

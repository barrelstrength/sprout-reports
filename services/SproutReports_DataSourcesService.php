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
	public function get($id)
	{
		$sources = $this->getAll();

		if (isset($sources[$id]))
		{
			return $sources[$id];
		}

		throw new Exception(Craft::t('Could not find data source with id {id}.', compact('id')));
	}

	/**
	 * @return null|SproutReportsBaseDataSource[]
	 */
	public function getAll()
	{
		if (is_null($this->dataSources))
		{
			$responses = craft()->plugins->call('registerSproutReportsDataSources');

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
						}
					}
				}
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
		$pluginHandle    = strtolower(sproutReports()->createHandle($pluginHandle));
		$dataSourceClass = strtolower(sproutReports()->createHandle($dataSourceClass));
		$dataSourceClass = str_replace($pluginHandle, '', $dataSourceClass);
		$dataSourceClass = str_replace('datasource', '', $dataSourceClass);

		return sprintf('%s.%s', $pluginHandle, $dataSourceClass);
	}
}

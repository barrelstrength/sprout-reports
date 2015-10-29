<?php
namespace Craft;

class SproutReportsService extends BaseApplicationComponent
{
	/**
	 * @var SproutReportsBaseDataSet[]
	 */
	protected $dataSets;

	/**
	 * @param $id
	 *
	 * @return SproutReportsModel
	 */
	public function getReport($id)
	{
		$result = SproutReportsRecord::model()->findById($id);

		if ($result)
		{
			return SproutReportsModel::populateModel($result);
		}
	}

	/**
	 * @return null|SproutReportsModel[]
	 */
	public function getReports()
	{
		$result = SproutReportsRecord::model()->findAll();

		if ($result)
		{
			return SproutReportsModel::populateModels($result);
		}
	}

	/**
	 * @return null|SproutReportsModel[]
	 */
	public function getEnabledReports()
	{
		$result = SproutReportsRecord::model()->findAllByAttributes(array('enabled' => 1));

		if ($result)
		{
			return SproutReportsModel::populateModels($result);
		}
	}

	/**
	 * @param int $id
	 *
	 * @return null|SproutReportsModel[]
	 */
	public function getReportsByGroupId($id)
	{
		$result = SproutReportsRecord::model()->findAllByAttributes(array('groupId' => $id));

		if ($result)
		{
			return SproutReportsModel::populateModels($result);
		}
	}

	/**
	 * @return SproutReportsBaseDataSet[]
	 */
	public function getDataSets()
	{
		if (is_null($this->dataSets))
		{
			$responses = craft()->plugins->call('registerSproutReportsDataProvider');

			if ($responses)
			{
				foreach ($responses as $plugin => $response)
				{
					if ($response instanceof SproutReportsDataProvider)
					{
						$dataSets = $response->getDataSets();

						if ($dataSets)
						{
							/**
							 * @var SproutReportsBaseDataSet $dataSet
							 */
							foreach ($dataSets as $dataSet)
							{
								if ($dataSet && $dataSet instanceof SproutReportsBaseDataSet)
								{
									$this->dataSets[$dataSet->getId($plugin)] = $dataSet;
								}
							}
						}
					}
				}
			}
		}

		return $this->dataSets;
	}

	/**
	 * @param $id
	 *
	 * @throws Exception
	 * @return SproutReportsBaseDataSet
	 */
	public function getDataSet($id)
	{
		$sets = $this->getDataSets();

		if (isset($sets[$id]))
		{
			return $sets[$id];
		}

		throw new Exception(Craft::t('The data set with id {id} was not found.', array('id' => $id)));
	}

	/**
	 * @param array $reports
	 */
	public function createReports(array $reports)
	{
		foreach ($reports as $report)
		{
			$this->createReport($report);
		}
	}

	/**
	 * @param SproutReportsBaseReportModel $report
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function createReport(SproutReportsBaseReportModel $report)
	{
		$record = new SproutReportsRecord();

		$record->name        = $report->getName();
		$record->handle      = $report->getHandle();
		$record->description = $report->getDescription();
		$record->options     = $report->getOptions();
		$record->dataSetId   = $report->getDataSetId();
		$record->enabled     = true;
		$record->groupId     = 1;

		if (!$record->save())
		{
			throw new Exception(print_r($record->getErrors(), true));
		}
	}
}

<?php
namespace Craft;

class SproutReports_ReportsService extends BaseApplicationComponent
{
	protected $reportRecord;

	public function __construct($reportRecord = null)
	{
	    $this->reportRecord = $reportRecord;
	    
	    if (is_null($this->reportRecord)) 
	    {
	      $this->reportRecord = SproutReports_ReportRecord::model();
	    }
	}
	/**
	 * Get a new blank item
	 *
	 * @param  array               $attributes
	 * @return SproutReports_ReportModel
	 */
	public function newModel($attributes = array())
	{
	    $model = new SproutReports_ReportModel();
	    $model->setAttributes($attributes);

	    return $model;
	}

	public function saveReport(SproutReports_ReportModel &$model)
	{	
		if ($id = $model->getAttribute('id')) 
		{
			if (null === ($record = $this->reportRecord->findByPk($id))) 
			{
			   throw new Exception(Craft::t('Can\'t find report with ID "{id}"', array('id' => $id)));
			}
		} 
		else 
		{
		   $record = $this->reportRecord->create();
		}

		// @TODO passing 'false' here allows us to save unsafe attributes
		// we should really update this to address validation better.
		$record->setAttributes($model->getAttributes(), false);

		if ($record->save()) {
		   // update id on model (for new records)
		   $model->setAttribute('id', $record->getAttribute('id'));
		   return true;
		} 
		else 
		{
		   $model->addErrors($record->getErrors());
		   return false;
		}
	}

	public function runReport($query)
	{
		echo "<pre>";
		print_r($query);
		echo "</pre>";
		die('fin');
		
		$results = craft()->db->createCommand($query)->query();		
		return $results;
	}

	public function deleteReportById($reportId)
	{
		if (!$reportId)
		{
			return false;
		}

		$report = new SproutReports_ReportRecord;

		return $report->deleteByPk($reportId);
	}

	public function getAllReports() 
	{
		$results = craft()->db->createCommand()
		            ->select('*')
		            ->from('sproutreports_reports')
		            ->queryAll();
		            
	  return $results;
	}	

	public function getReportById($reportId) 
	{
		// This doesn't work for some reason. customQuery returns empty.
		// $results = SproutReports_ReportRecord::model()->findById($reportId);

		$results = craft()->db->createCommand()
		            ->select('*')
		            ->from('sproutreports_reports')
		            ->where('id=:id', array(':id'=> $reportId))
		            ->queryRow();
		
	  return $results;
	}

	// @TODO - This doesn't do anything yet.  Might be nice to roll this
	// into runReport and allow an id: or a handle: to be sent to runReport
	public function getReportByHandle($handle) 
	{
		if (!$handle) return false;

		$results = craft()->db->createCommand()
		            ->select('*')
		            ->from('sproutreports_reports')
		            ->where('handle=:handle', array(':handle'=> $handle))
		            ->queryRow();
		
	  return $results;
	}
		
}
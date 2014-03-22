<?php

namespace Craft;

class SproutReportsVariable
{
	protected $plugin;

	public function __construct()
	{
		$this->plugin = craft()->plugins->getPlugin('sproutreports');
	}

	public function getName()
	{
		return $this->plugin->getName();
	}

	public function getVersion()
	{
		return $this->plugin->getVersion();
	}
	
	public function getAllReports() 
	{
		return craft()->sproutReports_reports->getAllReports();
	}	

	public function getReportById($reportId) 
	{
		return craft()->sproutReports_reports->getReportById($reportId);
	}	

	public function runReport($query, $report=null) 
	{
		return craft()->sproutReports_reports->runReport($query, $report);
	}

	public function allElementTypes() 
	{
		return craft()->elements->getAllElementTypes();
	}

	public function allSections() 
	{
		$sections		= array();
		$Allsections	= craft()->sections->getAllSections();

		foreach($Allsections as $section)
		{
			$sections[$section->handle] = array(
				'id'		=> $section->id,
				'type'		=> $section->type,
				'name'		=> $section->name,
				'handle'	=> $section->handle,
			);
		}

		return $sections;
	}

	public function allFields() 
	{
		$fields		= array();
		$groups		= array();
		$allFields	= craft()->fields->getAllFields();

		foreach ($allFields  as $field)
		{
			$fields[$field->id]	= $field->name;
		}

		return $fields;
	}
}
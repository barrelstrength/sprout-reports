<?php

namespace Craft;

class SproutReportsVariable
{
	/**
	 * Plugin Name
	 * Make your plugin name available as a variable 
	 * in your templates as {{ craft.yourPlugin.name }}
	 * 
	 * @return string
	 */
	public function getName()
	{
		$plugin = craft()->plugins->getPlugin('sproutreports');
	  return $plugin->getName();
	}

	/**
	 * Get plugin version
	 * 
	 * @return string
	 */
	public function getVersion()
	{
		$plugin = craft()->plugins->getPlugin('sproutreports');
	  return $plugin->getVersion();
	}
	
	public function getAllReports() 
	{
	  return craft()->sproutReports_reports->getAllReports();
	}	

	public function getReportById($reportId) 
	{
	  return craft()->sproutReports_reports->getReportById($reportId);
	}	

	public function runReport($query) 
	{
	  return craft()->sproutReports_reports->runReport($query);
	}
	

	public function allElementTypes() 
	{
	  return craft()->elements->getAllElementTypes();
	}

	public function allSections() 
	{
    $myarray = array();

    foreach(craft()->sections->getAllSections() as $section) {
			$mykey = $section->handle;
			$myarray[$mykey] = array(
			    'name' => $section->name,
			    'handle' => $section->handle,
			    'id' => $section->id,
			    'type' => $section->type
			    );
    }

    return $myarray;
  }

	public function allFields() 
	{
		$fgroups = array();
		$allfields = array();

		$fields = array();
		foreach( craft()->fields->getAllFields() as $field ) {
		        $mykey = $field->id;
		        $fields[$mykey] = $field->name;
		        }
		return $fields;
	}
}
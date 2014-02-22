<?php
namespace Craft;

use Paste;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Spreadsheet\Worksheet;

class SproutReports_ReportsController extends BaseController
{
    /**
     * Save a Query to the database
     * @return [type] [description]
     */
    public function actionSaveReport()
    {
      $this->requirePostRequest();

      $report = craft()->request->getPost();
      $report = SproutReports_ReportModel::populateModel($report);

      if (craft()->sproutReports_reports->saveReport($report))
      {
        craft()->userSession->setNotice(Craft::t('Report saved.'));
        $this->redirectToPostedUrl($report);
      }
      else
      {
        craft()->userSession->setError(Craft::t('Couldn’t save report.'));

        // Send the field back to the template
        craft()->urlManager->setRouteVariables(array(
          'report' => $report
        ));
      }
      
      
    }

    /**
     * Run a Saved Query
     * Return query data to the browser and optionally a output a csv file
     * 
     * @return mixed Query data
     */
    public function actionRunReport()
    { 
      $this->requirePostRequest();

      // Get our Report Info
      $reportId = craft()->request->getPost('reportId');
      $report = craft()->sproutReports_reports->getReportById($reportId);

      // Run the Report Query
      $results = craft()->sproutReports_reports->runReport($report['customQuery']);

      // Trigger data Export to CSV
      if (craft()->request->getPost('exportData'))
      {
        $this->_exportData($report, $results);

        // This doesn't do anything.
        // craft()->userSession->setNotice(Craft::t('Report Exported.'));
        // $this->redirectToPostedUrl($report);
      }
      else
      {        
        craft()->urlManager->setRouteVariables(array(
          'report' => $report, 
          'results' => $results
        ));
        
        // $route = craft()->urlManager->parseUrl(craft()->request);
        // craft()->runController('sproutreports/results');
        // craft()->end();
      }
  		
    }

    /**
     * Delete Report
     * 
     * @return void
     */
    public function actionDeleteReport()
    {
      $this->requirePostRequest();
      
      // craft()->userSession->requirePermission('deleteSproutReports');

      $reportId = craft()->request->getRequiredPost('id');
      craft()->sproutReports_reports->deleteReportById($reportId);

      $this->redirectToPostedUrl();
    }

    /**
     * Export Data as CSV
     * 
     * @param  object $results Results from SQL query
     * @return file            csv file
     */
    private function _exportData($report, $results)
    {
      $worksheet = new Worksheet();

      foreach($results as $key => $row) 
      {
        // unset($row['photo']);
        if($key == 0) {
          $columnNames = array_keys($row);
          $worksheet->insertRecord($columnNames);
        }
        
        $worksheet->insertRecord($row);
      }

      $excel = new SimpleExcel();
      $excel->insertWorksheet($worksheet);
      
      $reportName = str_replace(' ', '', $report['name']);
      $filename = $reportName . '-'. date('Ymd-hms') . '.csv';
      // $excel->exportFile( CRAFT_STORAGE_PATH . $filename , 'CSV');
      $excel->exportFile('php://output', 'CSV', array('filename' => $filename));
    }

    public function actionCreateQuery()
    {
      $this->requirePostRequest();
      $exportData = craft()->request->getPost('exportData');
      $queryText = craft()->request->getPost('queryText');
      $elementType = craft()->request->getPost('elementType');
      $sectionName = craft()->request->getPost('sectionName');
      $isUsersQuery = craft()->request->getPost('isUsersQuery');
      $tableCols = array();
      $qType = 'Query';

      if($queryText) 
      {
        $myentries = craft()->db->createCommand($queryText)->queryAll();
        if(sizeof($myentries) > 0) 
        {
          $tableCols = array_keys($myentries[0]);
        }
			}
      elseif($isUsersQuery) 
      {
        $qType = 'Users';
        $myentries = craft()->db->createCommand()
            ->select('photo, id, username, firstName, lastName, email, admin AS isAdmin')
            ->from('users u')
            ->order("lastName ASC, firstName ASC")
            ->queryAll();
        
        if(sizeof($myentries) > 0) 
        {
          $tableCols = array_keys($myentries[0]);
          
          foreach($myentries as $key=>$item) {
            $temp_photo = UrlHelper::getResourceUrl('userphotos/'.$item['username'].'/100/'.$item['photo']);
            $myentries[$key]['photo'] = '<img src="'.$temp_photo.'" width="100" height="100" />';
          }
        }
      }
      elseif($sectionName) 
      {
        $criteria = craft()->sections->getSectionById($sectionName);
        $reportName = 'Section: '.$criteria->name;
        $qType = 'Elements'.$criteria->name;
        $sectionid = $criteria->id;

	      $myentriestemp = craft()->db->createCommand()
	          ->select('c.*')
	          ->from('entries e')
	          ->join('content c', 'e.id = c.elementId')
	          ->where( "sectionId = $sectionid" )
	          ->queryAll();

	      if(sizeof($myentriestemp) > 0) 
	      {
          $newarrtemp = $this->_transpose($myentriestemp);
          $newarr = array();
          $newarr2 = array();
          $newarr3 = array();
          $tableCols = array();
          $tempct = 0;

          foreach($myentriestemp[0] as $tablecol=>$item) {
            $newarr[$tablecol] = $newarrtemp[$tempct];
            ++$tempct;
          }

          foreach($newarr as $key=>$row) {
            if((sizeof(array_filter($row)) > 0)) 
            {
              $newarr2[$key] = $row;
              $tableCols[] = $key;
            }
          }

          $newarr3 = $this->_transpose($newarr2);
          foreach($newarr3 as $key=>$row) {
            foreach($tableCols as $tkey=>$titem) {
              $myentries[$key][$titem] = $row[$tkey];
            }
          }
        }
	    }

		  // Trigger data Export to CSV
      if (craft()->request->getPost('exportData'))
      {
        // $this->_exportData($report, $results);
      }
		  
		  return craft()->urlManager->setRouteVariables(array(
        'queryText' => $queryText, 
        'sectionName' => $sectionName, 
        'myEntries' => $myentries, 
        'tableCols' => $tableCols, 
        'reportName' => $reportName
      ));

    }

    private function _transpose($array) 
    {
      array_unshift($array, null);
      return call_user_func_array('array_map', $array);
    }



}

<?php
namespace Craft;

use Paste;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Spreadsheet\Worksheet;

class SproutReports_ReportsController extends BaseController
{
	/**
	 * Saves a report query to the database
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
			craft()->userSession->setError(Craft::t('Could not save report.'));
			craft()->urlManager->setRouteVariables(array('report' => $report));
		}
	}

	/**
	 * Runs a previously saved report query
	 * 
	 * @return mixed	Report output or report output as CSV
	 */
	public function actionRunReport()
	{ 
		$this->requirePostRequest();

		$reportId	= craft()->request->getPost('reportId');
		$report		= craft()->sproutReports_reports->getReportById($reportId);
		$results	= craft()->sproutReports_reports->runReport($report['customQuery']);

		// Export to CSV
		if (craft()->request->getPost('exportData'))
		{
			$this->exportDataToCsv($report, $results);
		}
		else
		{
			craft()->urlManager->setRouteVariables(
				array(
					'report'	=> $report, 
					'results'	=> $results
				)
			);
		}
	}

	public function actionDeleteReport()
	{
		$this->requirePostRequest();

		$reportId = craft()->request->getRequiredPost('id');

		craft()->sproutReports_reports->deleteReportById($reportId);
		$this->redirectToPostedUrl();
	}

	public function actionCreateQuery()
	{
		$this->requirePostRequest();

		$exportData		= craft()->request->getPost('exportData');
		$queryText		= craft()->request->getPost('queryText');
		$elementType	= craft()->request->getPost('elementType');
		$sectionName	= craft()->request->getPost('sectionName');
		$isUsersQuery	= craft()->request->getPost('isUsersQuery');
		$tableCols		= array();
		$qType			= 'Query';

		if ($queryText) 
		{
			$myentries = craft()->db->createCommand($queryText)->queryAll();
			
			if (sizeof($myentries) > 0) 
			{
				$tableCols = array_keys($myentries[0]);
			}
		}
		elseif($isUsersQuery) 
		{
			$qType		= 'Users';
			$myentries	= craft()->db->createCommand()
						->select('photo, id, username, firstName, lastName, email, admin AS isAdmin')
						->from('users u')
						->order('lastName ASC, firstName ASC')
						->queryAll();

			if (sizeof($myentries) > 0) 
			{
				$tableCols = array_keys($myentries[0]);

				foreach($myentries as $key => $item)
				{
					$resourceUrl				= 'userphotos/'.$item['username'].'/100/'.$item['photo'];
					$temp_photo					= UrlHelper::getResourceUrl($resourceUrl);
					$myentries[$key]['photo']	= '<img src="'.$temp_photo.'" width="100" height="100" />';
				}
			}
		}
		elseif($sectionName) 
		{
			$criteria		= craft()->sections->getSectionById($sectionName);
			$reportName		= 'Section: '.$criteria->name;
			$sectionid		= $criteria->id;
			$qType			= 'Elements'.$criteria->name;

			$myentriestemp	= craft()->db->createCommand()
							->select('c.*')
							->from('entries e')
							->join('content c', 'e.id = c.elementId')
							->where('sectionId = '.$sectionid)
							->queryAll();

			if (sizeof($myentriestemp) > 0) 
			{
				$newarrtemp	= $this->transpose($myentriestemp);
				$newarr		= array();
				$newarr2	= array();
				$newarr3	= array();
				$tableCols	= array();
				$tempct		= 0;

				foreach($myentriestemp[0] as $tablecol=>$item)
				{
					$newarr[$tablecol] = $newarrtemp[$tempct];
					++$tempct;
				}

				foreach($newarr as $key=>$row)
				{
					if((sizeof(array_filter($row)) > 0))
					{
						$newarr2[$key] = $row;
						$tableCols[] = $key;
					}
				}

				$newarr3 = $this->transpose($newarr2);

				foreach($newarr3 as $key=>$row)
				{
					foreach($tableCols as $tkey=>$titem)
					{
						$myentries[$key][$titem] = $row[$tkey];
					}
				}
			}
		}


		// Trigger data Export to CSV
		if (craft()->request->getPost('exportData'))
		{
			// $this->exportDataToCsv($report, $results);
		}

		return craft()->urlManager->setRouteVariables(
			array(
				'queryText'		=> $queryText, 
				'sectionName'	=> $sectionName, 
				'myEntries'		=> $myentries, 
				'tableCols'		=> $tableCols, 
				'reportName'	=> $reportName
			)
		);
	}


	/**
	 * Export Data as CSV
	 * 
	 * @param  object	$results	Results from SQL query
	 * @return buffer				The CSV output
	 */
	protected function exportDataToCsv($report, $results)
	{
		$worksheet = new Worksheet();

		foreach($results as $key => $row) 
		{
			if($key == 0)
			{
				$columnNames = array_keys($row);
				$worksheet->insertRecord($columnNames);
			}

			$worksheet->insertRecord($row);
		}

		$excel = new SimpleExcel();
		$excel->insertWorksheet($worksheet);

		$reportName	= str_replace(' ', '', $report['name']);
		$filename	= $reportName . '-'. date('Ymd-hms') . '.csv';

		$excel->exportFile('php://output', 'CSV', array('filename' => $filename));
	}

	protected function transpose($array) 
	{
		array_unshift($array, null);
		return call_user_func_array('array_map', $array);
	}
}

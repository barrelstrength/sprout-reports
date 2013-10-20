<?php
namespace Craft;
use Paste;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Spreadsheet\Worksheet;

class CraftDiscover_ReportsController extends BaseController
{
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

        if($queryText) {
            $myentries = craft()->db->createCommand($queryText)->queryAll();
            if(sizeof($myentries) > 0) {
                $tableCols = array_keys($myentries[0]);
                }

            }

        elseif($isUsersQuery) {
            $qType = 'Users';
            $myentries = craft()->db->createCommand()
                ->select('photo, id, username, firstName, lastName, email, admin AS isAdmin')
                ->from('users u')
                ->order("lastName ASC, firstName ASC")
                ->queryAll();
            if(sizeof($myentries) > 0) {
                $tableCols = array_keys($myentries[0]);
                foreach($myentries as $key=>$item) {
                    $temp_photo = UrlHelper::getResourceUrl('userphotos/'.$item['username'].'/100/'.$item['photo']);
                    $myentries[$key]['photo'] = '<img src="'.$temp_photo.'" width="100" height="100" />';
                    }
                }
        }

        elseif($sectionName) {
            $criteria = craft()->sections->getSectionById($sectionName);
            $qType = 'Elements'.$criteria->name;
            $sectionid = $criteria->id;

            $combination = craft()->request->getPost('combination');
            $fieldname = craft()->request->getPost('fieldname');
            $operation = craft()->request->getPost('operation');
            $comparevalue = craft()->request->getPost('comparevalue');

    $mystuff = array();

            foreach($fieldname as $key => $item) {
                if($item && $item != '') {
                    if($combination[$key] != 'not' && $combination[$key] != '') {
                            $mystuff[] = array($combination[$key], "{$fieldname[$key]} {$operation[$key]}", "{$comparevalue[$key]}" );
                        }
                    }
                }

            $myentries = craft()->db->createCommand()
                ->select('c.*')
                ->from('entries e')
                ->join('content c', 'e.id = c.elementId')
                ->where( "sectionId = $sectionid" )
                ->queryAll();

            if(sizeof($myentries) > 0) {
                $tableCols = array_keys($myentries[0]);
                }
            }

    if($exportData == 'Y') {
        $worksheet = new Worksheet();

        foreach($myentries as $key=>$row) {
            unset($row['photo']);
            if($key == 0) {
                $mycolnames = array_keys($row);
                $worksheet->insertRecord($mycolnames);
                }
            $worksheet->insertRecord($row);
            }

        $excel = new SimpleExcel();
        $excel->insertWorksheet($worksheet);

        $filename = 'export'.$qType.time().'.csv';
        $excel->exportFile(CRAFT_STORAGE_PATH.$filename, 'CSV');
        $excel->exportFile('php://output', 'CSV', array('filename' => $filename));
        }
    return craft()->urlManager->setRouteVariables(array('queryText' =>$queryText, 'sectionName' => $sectionName, 'myEntries' => $myentries, 'tableCols' => $tableCols));

  //      echo Paste\Pre::render( craft()->fields->getAllGroups() );
  //      echo Paste\Pre::render( craft()->fields->getAllFields() );



         die();

    }

}

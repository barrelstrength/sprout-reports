<?php
namespace Craft;
use Paste;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Spreadsheet\Worksheet;

class Discover_ReportsController extends BaseController
{

    public function actionSavedQuery()
    {
        $this->requirePostRequest();
        $exportData = craft()->request->getPost('exportData');
        $qType = craft()->request->getPost('queryType');
        $reportName = '';

        $userfields = "SELECT GROUP_CONCAT(CONCAT('c.field_',handle)) AS fhandle, groupId FROM craft_fields WHERE groupId = 2 AND handle != 'profilePhoto' GROUP BY groupId;";
        $alluserfields =  craft()->db->createCommand($userfields)->queryScalar();

        $sql = "SELECT 1 AS NA";
        if($qType == 'papersSubmitted') {
            $reportName = 'Papers Submitted, with Status';
            $sql = "SELECT  c.title, c.field_paperStatus, c.field_jobTitle, c.field_documentType, c.field_city,
                            c.field_state, c.field_postalCode, c.field_countryCode,
                            c.field_paperSummary, c.field_AuthorNameSubmitted, c.field_coAuthorsSubmitted,
                            c.field_department, c.field_organization,
                            c.dateCreated, c.dateUpdated
                FROM
                    craft_entries e
                    LEFT JOIN craft_content c ON c.elementId = e.id
                WHERE
                    e.sectionId = 7";
            }
        elseif($qType == "allUsers") {
            $reportName = 'User Data';
            $sql = "SELECT  u.firstName, u.lastName, u.email, u.dateCreated, u.dateUpdated, {$alluserfields}
                     FROM
                        craft_users u
                        LEFT JOIN craft_content c ON u.id = c.elementId;";
            }
        elseif($qType == "paperOwners") {
            $sql = "SELECT  u.firstName, u.lastName, u.email, {$alluserfields}, c2.field_paperStatus,
                            e.dateCreated AS paperSubmittedDate, e.dateUpdated AS paperUpdatedDate
                    FROM
                        craft_entries e
                    LEFT JOIN craft_users u ON e.authorId = u.id
                    LEFT JOIN craft_content c2 ON c2.elementId = e.id
                    LEFT JOIN craft_content c ON u.id = c.elementId
                    WHERE
                        e.sectionId = 7";
            }
        elseif($qType == "paidReg") {
            $reportName = 'All Paid Registrations';
            $sql = "SELECT  u.firstName, u.lastName, u.email, {$alluserfields},
                            p.title, o.reference AS referenceNumber, o.`data` AS extraNotes,
                            o.dateCreated AS purchaseDate
                    FROM
                        craft_commerce_orders o
                        LEFT JOIN craft_commerce_orders_products p ON o.id = p.orderId
                        LEFT JOIN craft_commerce_orders_products_options op ON op.orderProductId = p.orderId AND op.optionSetHandle = 'conferenceRegistration'
                        LEFT JOIN craft_users u ON o.userId = u.id
                        LEFT JOIN craft_content c ON u.id = c.elementId";
            }

        $myentries = craft()->db->createCommand($sql)->queryAll();
            if(sizeof($myentries) > 0) {


        if($qType == "paidReg") {
            foreach($myentries as $key=>$item) {
                $mynotes = json_decode($item['extraNotes'],TRUE);
                $noteoutput = "";
                foreach($mynotes as $nkey => $nitem) {
                    $myentries[$key]["{$nkey}"] = ($nitem['content'] == 1) ? "Y" : "N";
                    }
                unset($myentries[$key]['extraNotes']);
                }
            }

             $tableCols = array_keys($myentries[0]);
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
    return craft()->urlManager->setRouteVariables(array('myEntries' => $myentries, 'tableCols' => $tableCols, 'reportName' => $reportName));
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
            $reportName = 'Section: '.$criteria->name;
            $qType = 'Elements'.$criteria->name;
            $sectionid = $criteria->id;



            $myentriestemp = craft()->db->createCommand()
                ->select('c.*')
                ->from('entries e')
                ->join('content c', 'e.id = c.elementId')
                ->where( "sectionId = $sectionid" )
                ->queryAll();

            if(sizeof($myentriestemp) > 0) {

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
                    if((sizeof(array_filter($row)) > 0)) {
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
    return craft()->urlManager->setRouteVariables(array('queryText' =>$queryText, 'sectionName' => $sectionName, 'myEntries' => $myentries, 'tableCols' => $tableCols, 'reportName' => $reportName));

  //      echo Paste\Pre::render( craft()->fields->getAllGroups() );
  //      echo Paste\Pre::render( craft()->fields->getAllFields() );



         die();

    }

    private function _transpose($array) {
        array_unshift($array, null);
        return call_user_func_array('array_map', $array);
        }

}

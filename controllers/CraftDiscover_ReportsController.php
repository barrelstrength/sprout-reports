<?php
namespace Craft;
use Paste as Paste;

class CraftDiscover_ReportsController extends BaseController
{
    public function actionCreateQuery()
    {
        $this->requirePostRequest();
        $queryText = craft()->request->getPost('queryText');
        $elementType = craft()->request->getPost('elementType');
        $sectionName = craft()->request->getPost('sectionName');
        $tableCols = array();

        if($queryText) {
            $myentries = craft()->db->createCommand($queryText)->queryAll();
            if(sizeof($myentries) > 0) {
                $tableCols = array_keys($myentries[0]);
                }
            }

        elseif($sectionName) {
            $criteria = craft()->sections->getSectionById($sectionName);
            $sectionid = $criteria->id;

            $myentries = craft()->db->createCommand()
                ->select('c.*')
                ->from('entries e')
                ->join('content c', 'e.id = c.elementId')
                ->where('sectionId=:id', array(':id'=>$sectionid))
                ->queryAll();
            if(sizeof($myentries) > 0) {
                $tableCols = array_keys($myentries[0]);
                }
            }


       return craft()->urlManager->setRouteVariables(array('queryText' =>$queryText, 'sectionName' => $sectionName, 'myEntries' => $myentries, 'tableCols' => $tableCols));

  //      echo Paste\Pre::render( craft()->fields->getAllGroups() );
  //      echo Paste\Pre::render( craft()->fields->getAllFields() );



         die();

    }

}

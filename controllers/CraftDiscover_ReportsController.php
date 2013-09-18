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

        if($queryText) {
            $query = craft()->db->createCommand($queryText)->queryAll();

            }

        elseif($elementType) {
            $criteria = craft()->elements->getCriteria($elementType);
            $elements = craft()->elements->findElements($criteria);

            }

        elseif($sectionName) {


            }


        craft()->urlManager->setRouteVariables(array('queryText' =>$queryText));

        echo Paste\Pre::render( craft()->fields->getAllGroups() );
        echo Paste\Pre::render( craft()->fields->getAllFields() );



         die();

    }

}

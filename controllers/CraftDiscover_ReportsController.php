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

        elseif($elementType || $sectionName) {
            $criteria = craft()->elements->getCriteria($elementType);
            $elements = craft()->elements->findElements($criteria);

            }


        craft()->urlManager->setRouteVariables(array('queryText' =>$queryText));

        foreach(craft()->sections->getAllSections() as $section) {
            echo Paste\Pre::render( $section );
        }

         die();

    }

}

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

        if($queryText) {
            $query = craft()->db->createCommand($queryText)->queryAll();
            echo Paste\Pre::render($query); die();
            }

        elseif($elementType) {
            $criteria = craft()->elements->getCriteria($elementType);
            $elements = craft()->elements->findElements($criteria);
            echo Paste\Pre::render($elements); die();
            }

        return $this->redirectToPostedUrl(array('queryText' =>$queryText, 'test' => 'hi'));

    }

}

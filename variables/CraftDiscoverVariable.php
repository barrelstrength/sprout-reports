<?php

namespace Craft;

class CraftDiscoverVariable
{

public function allElementTypes() {
    return craft()->elements->getAllElementTypes();
    }

public function allSections() {
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

}

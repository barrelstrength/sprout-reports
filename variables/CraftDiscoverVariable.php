<?php

namespace Craft;

class CraftDiscoverVariable
{

public function allElementTypes() {
    return craft()->elements->getAllElementTypes();
    }

}

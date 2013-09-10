<?php
namespace Craft;

class CraftDiscover_ElementModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'query' => array(AttributeType::String, 'maxLength' => 4000),﻿
        );
    }
}

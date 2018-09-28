<?php

namespace RRZE\RRZESearch\Ports\Engines\Adapters;


use RRZE\RRZESearch\Ports\Engines\Classes\GoogleSearch;

class Google2Adapter extends GoogleSearch
{
    const NAME = 'Google Custom Search 2';
    const LABEL = 'Google Web Search';
    const LINK_LABEL = 'Google\'s Privacy Policy';

    public static function getName(): string
    {
        return self::NAME;
    }

    public static function getLabel(): string
    {
        return self::LABEL;
    }

    public static function getLinkLabel(): string
    {
        return self::LINK_LABEL;
    }
}
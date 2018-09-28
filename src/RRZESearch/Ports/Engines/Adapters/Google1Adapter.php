<?php

namespace RRZE\RRZESearch\Ports\Engines\Adapters;


use RRZE\RRZESearch\Ports\Engines\Classes\GoogleSearch;

class Google1Adapter extends GoogleSearch
{
    const NAME = 'Google Custom Search 1';
    const LABEL = 'Google FAU CS';
    const LINK_LABEL = 'READ FAU\'s Instructions';

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
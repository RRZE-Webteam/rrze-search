<?php

namespace RRZE\RRZESearch\Ports\Engines\Adapters;


use RRZE\RRZESearch\Ports\Engines\Classes\WordPressSearch;

class WordPressAdapter extends WordPressSearch
{
    const NAME = 'WordPress Native Search';
    const LABEL = 'Default';
    const LINK_LABEL = 'Privacy Policy';

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
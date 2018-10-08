<?php

namespace RRZE\RRZESearch\Infrastructure\Engines\Adapters;

use RRZE\RRZESearch\Infrastructure\Engines\Foundations\GoogleSearch;

/**
 * Google Custom Search (Variant 2)
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Ports
 */
class Google2Adapter extends GoogleSearch
{
    /**
     * Search engine name
     *
     * @var string
     */
    const NAME = 'Google Custom Search 2';
    /**
     * Search engine label (with optional placeholder %s for link label)
     *
     * @var string
     */
    const LABEL = 'Google Web Search%s';
    /**
     * Privacy policy / instruction page link label
     *
     * @var string
     */
    const LINK_LABEL = 'Google Privacy Policy';
    /**
     * Google Custom Search ID
     *
     * @var string
     */
    const ID = '011945293402966620832:n0bvaqo6yl4';

    public static function getName(): string
    {
        return __('Google FAU Search', 'rrze-search');
    }
}
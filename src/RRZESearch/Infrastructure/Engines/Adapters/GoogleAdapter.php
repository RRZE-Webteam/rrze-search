<?php

namespace RRZE\RRZESearch\Infrastructure\Engines\Adapters;

use RRZE\RRZESearch\Infrastructure\Engines\Foundations\GoogleSearch;

/**
 * Google Custom Search (Variant 1)
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Ports
 */
class GoogleAdapter extends GoogleSearch
{
    /**
     * Search engine label (with optional placeholder %s for link label)
     *
     * @var string
     */
    const LABEL = 'Google FAU Search';
    /**
     * Privacy policy / instruction page link label
     *
     * @var string
     */
    const LINK_LABEL = 'Privacy Disclaimer';

}
<?php

namespace RRZE\RRZESearch\Infrastructure\Engines\Adapters;

use RRZE\RRZESearch\Infrastructure\Engines\Foundations\WordPressSearch;

/**
 * WordPress Native Search
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Ports
 */
class WordPressAdapter extends WordPressSearch
{
    /**
     * Search engine name
     *
     * @var string
     */
    const NAME = 'WordPress Native Search';
    /**
     * Search engine label (with optional placeholder %s for link label)
     *
     * @var string
     */
    const LABEL = 'Internal Search%s';
    /**
     * Privacy policy / instruction page link label
     *
     * @var string
     */
    const LINK_LABEL = 'Privacy Policy';
}
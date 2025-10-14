<?php

namespace RRZE\RRZESearch\Infrastructure\Engines\Adapters;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Infrastructure\Engines\Foundations\GoogleSearch;

/**
 * Configures the Google Custom Search adapter used by RRZE Search.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
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

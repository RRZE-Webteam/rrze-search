<?php
/***********************************************************************************
 *
 * RRZE-Webteam
 * RRZE Search WordPress Plugin v1.0
 *
 * current file: SEARCH ENGINE Adapter Template
 *
 * /***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 tollwerk GmbH <info@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

// Use the following namespace when duplicating this template for a real adapter.
namespace RRZE\RRZESearch\Infrastructure\Engines\Template;

// Extend the corresponding concrete engine class created from SearchEngine-Class.

/**
 * Template for implementing a concrete search engine adapter.
 *
 * Replace the placeholders with provider-specific metadata and translations
 * before registering the adapter with the plugin.
 *
 * @package RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SearchEngineAdapter extends SearchEngineClass
{
    /**
     * Internal identifier for this search engine.
     */
    const NAME = 'Search Engine Name';
    /**
     * Label displayed to end users on the frontend.
     */
    const LABEL = 'Search Engine FE Label';
    /**
     * Label for the privacy policy link associated with the engine.
     */
    const LINK_LABEL = 'Privacy Policy Link';

    /**
     * Returns the translated name used throughout the admin interface.
     */
    public static function getName(): string
    {
        return __('Your Label with Translation Support', 'rrze-search');
    }

    /**
     * Returns the frontend label for display in selectors and badges.
     */
    public static function getLabel(): string
    {
        return self::LABEL;
    }

    /**
     * Returns the label used when linking to privacy information.
     */
    public static function getLinkLabel(): string
    {
        return self::LINK_LABEL;
    }
}

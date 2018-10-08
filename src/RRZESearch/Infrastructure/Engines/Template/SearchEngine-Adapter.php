<?php
/***********************************************************************************
 *
 * RRZE-Websteam
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

// Use the following NAMESPACE
namespace RRZE\RRZESearch\Infrastructure\Engines\Adapters;

// Extend to corresponding Foundation Class
use RRZE\RRZESearch\Infrastructure\Engines\Foundations\SearchEngineClass;

class SearchEngineAdapter extends SearchEngineClass
{
    // You can use constants to hold value or see below for alternative approach
    const NAME = 'Search Engine Name';
    const LABEL = 'Search Engine FE Label';
    const LINK_LABEL = 'Privacy Policy Link';

    public static function getName(): string
    {
        return __('Your Label with Translation Support', 'rrze-search');
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
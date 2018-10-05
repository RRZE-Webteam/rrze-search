<?php

/**
 * data
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage RRZE\RRZESearch\Infrastructure\Engines\Foundations
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
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

namespace RRZE\RRZESearch\Infrastructure\Engines\Foundations;

use RRZE\RRZESearch\Domain\Contract\Engine;

/**
 * Abstract search engine base class
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Ports
 */
abstract class AbstractSearchEngine implements Engine
{
    /**
     * Search engine name
     *
     * @var string
     */
    const NAME = 'Abstract name';
    /**
     * Search engine label (with optional placeholder %s for link label)
     *
     * @var string
     */
    const LABEL = 'Abstract label';
    /**
     * Privacy policy / instruction page link label
     *
     * @var string
     */
    const LINK_LABEL = 'Abstract link label';
    /**
     * Redirect Link
     *
     * @var string
     */
    const REDIRECT_LINK = 'Abstract redirect link';

    /**
     * Return the search engine name
     *
     * @return string Name
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * Return redirect link
     *
     * @return string
     */
    public static function getRedirectLink(): string
    {
        return static::REDIRECT_LINK;
    }

    /**
     * Return the search engine label
     *
     * @return string Label
     */
    public static function getLabel(): string
    {
        return static::LABEL;
    }

    /**
     * Return the search engine privacy policy / instruction page link label
     *
     * @return string Link label
     */
    public static function getLinkLabel(): string
    {
        return static::LINK_LABEL;
    }
}
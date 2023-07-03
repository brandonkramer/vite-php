<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Range;

use CaptainHook\App\Git\Range;
use CaptainHook\App\Git\Ref;

/**
 * Generic range implementation
 *
 * Most simple range implementation
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhookphp/captainhook
 * @since   Class available since Release 5.15.0
 */
class Generic implements Range
{
    /**
     * Starting reference
     *
     * @var \CaptainHook\App\Git\Ref
     */
    private Ref $from;

    /**
     * Ending reference
     *
     * @var \CaptainHook\App\Git\Ref
     */
    private Ref $to;

    /**
     * Constructor
     *
     */
    public function __construct(Ref $from, Ref $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * Return the git reference
     *
     * @return \CaptainHook\App\Git\Ref
     */
    public function from(): Ref
    {
        return $this->from;
    }

    /**
     * @return \CaptainHook\App\Git\Ref
     */
    public function to(): Ref
    {
        return $this->to;
    }
}

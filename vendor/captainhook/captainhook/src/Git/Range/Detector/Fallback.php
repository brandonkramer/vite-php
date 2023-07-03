<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Range\Detector;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Git\Range;
use CaptainHook\App\Git\Range\Detecting;
use CaptainHook\App\Git\Ref;

/**
 * Fallback Detector
 *
 * If no detection strategy matches the fallback detector is used to find the right range.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhookphp/captainhook
 * @since   Class available since Release 5.15.0
 */
class Fallback implements Detecting
{
    /**
     * Returns the fallback range
     *
     * @param  \CaptainHook\App\Console\IO $io
     * @return \CaptainHook\App\Git\Range\Generic[]
     */
    public function getRanges(IO $io): array
    {
        return [
            new Range\Generic(
                new Ref\Generic($io->getArgument('previousHead', 'HEAD@{1}')),
                new Ref\Generic('HEAD')
            )
        ];
    }
}

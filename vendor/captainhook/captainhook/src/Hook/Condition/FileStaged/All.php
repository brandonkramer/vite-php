<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\FileStaged;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition\FileStaged;
use SebastianFeldmann\Git\Repository;

/**
 * Class All
 *
 * The FileStaged condition is applicable for `pre-commit` hooks.
 * It checks if all configured files are staged for commit.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhookphp/captainhook
 * @since   Class available since Release 5.2.0
 */
class All extends FileStaged
{
    /**
     * Check if all the configured files are staged for commit
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        return $this->allFilesInHaystack($this->filesToWatch, $this->getStagedFiles($repository));
    }
}

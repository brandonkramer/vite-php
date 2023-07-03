<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\Command\Hook;

use CaptainHook\App\Console\Command\Hook;
use CaptainHook\App\Hooks;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class PostCheckout
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhookphp/captainhook
 * @since   Class available since Release 4.1.0
 */
class PostCheckout extends Hook
{
    /**
     * Hook to execute.
     *
     * @var string
     */
    protected $hookName = Hooks::POST_CHECKOUT;

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addArgument('previousHead', InputArgument::OPTIONAL, 'Previous HEAD');
        $this->addArgument('newHead', InputArgument::OPTIONAL, 'New HEAD');
        $this->addArgument('mode', InputArgument::OPTIONAL, 'Checkout mode 1 branch 0 file');
    }
}

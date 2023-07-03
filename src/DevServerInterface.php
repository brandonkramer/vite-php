<?php

/**
 * Contract for the Dev Server.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

/**
 * Class DevServerInterface
 */
interface DevServerInterface
{
    /**
     * Start and listen to the Vite dev server client, serve the source files
     * through hooks and inject Vite's scripts for HMR.
     *
     * This should only run on a local/dev environment as it's performance sensitive.
     *
     * @param string $port Optional. The server port number is set to 3000 by default.
     *
     * @return void
     */
    public function start(string $port = '3000');
}

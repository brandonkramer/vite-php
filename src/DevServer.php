<?php

/**
 * Responsible for listening to Vite's dev server, loading the source files and
 * injecting scripts from the Vite dev server for HMR.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

/**
 * Class DevServer
 */
class DevServer implements DevServerInterface
{
    /**
     * The assets.
     *
     * @var AssetsInterface
     */
    protected AssetsInterface $assets;

    /**
     * The vite dev server port number.
     *
     * @var string
     */
    protected string $portNumber;

    /**
     * Inject dependencies.
     */
    public function __construct(AssetsInterface $assets)
    {
        $this->assets = $assets;
    }

    /**
     * @inheritDoc
     */
    public function start(string $port = '3000')
    {
        /**
         * Set the port number to listen to.
         */
        $this->portNumber = $port;

        /**
         * Checks if the Vite client exists.
         */
        if ($this->isViteClientActive()) {
            \add_action('wp_head', [$this, 'loadViteScript'], -99);
            \add_filter('script_loader_tag', [$this, 'loadScriptsAsModule'], 999, 3);
            \add_filter("assets_{$this->assets->getHook()}_url", [$this, 'loadSourceFiles'], 99, 3);
        }
    }

    /**
     * This will inject vite's dev server script.
     *
     * @return void
     */
    public function loadViteScript()
    {
        echo '<script type="module" src="' . $this->getViteClientUrl() . '"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
        echo '<script type="module">window.process = {env: {NODE_ENV: "development"}}</script>';
    }

    /**
     * Ensures we use the import syntax when loading our un-compiled source scripts.
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     *
     * @return string
     */
    public function loadScriptsAsModule(string $tag, string $handle, string $src): string
    {
        return \strpos($src, "{$this->assets->getDirname()}/{$this->assets->getOutDir()}") !== false
            ? '<script type="module" src="' . \esc_url($src) . '"></script>' // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
            : $tag;
    }

    /**
     * Load source files instead of our production files.
     *
     * @param string $url The asset URL.
     * @param array<string, string|string[]|bool> $manifest The manifest data of the asset.
     * @param string $file Relative path to the asset file in the "src" folder.
     *
     * @return string
     */
    public function loadSourceFiles(string $url, array $manifest, string $file): string
    {
        $sourceFile = $manifest['src'];
        $sourceFilePath = "{$this->assets->getDir()}/{$this->assets->getRoot()}/{$sourceFile}";

        if (!\is_readable($sourceFilePath)) {
            if (\strpos($sourceFilePath, '.css') !== false) {
                $sourceFile = \str_replace('.css', '.pcss', $sourceFile);
            }
        }

        return \get_home_url() . ":{$this->portNumber}/{$sourceFile}";
    }

    /**
     * Get the local vite client URL that will be exposed when the Vite dev server
     * is running.
     *
     * @return string
     */
    protected function getViteClientUrl(): string
    {
        return \get_home_url() . ":{$this->portNumber}/@vite/client";
    }

    /**
     * Will check if the Vite client exist to understand if Vite dev server has been started,
     * so it will fall back to load the production files if Vite dev server is not active.
     *
     * This condition should only be run on a local/dev environment.
     *
     * @return bool
     */
    protected function isViteClientActive(): bool
    {
        // phpcs:disable
        $curl = \curl_init();
        \curl_setopt_array($curl, [
            CURLOPT_URL => (
            $this->getViteClientUrl()
            ),
            CURLOPT_RETURNTRANSFER => true,
        ]);
        \curl_exec($curl);
        $errors = \curl_error($curl);
        $response = \curl_getinfo($curl, CURLINFO_HTTP_CODE);
        \curl_close($curl);
        // phpcs:enable

        return !($errors || $response !== 200);
    }
}

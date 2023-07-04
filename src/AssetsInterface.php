<?php

/**
 * Contract for the Assets Service.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

/**
 * Class AssetsInterface
 */
interface AssetsInterface
{
    /**
     * Set configurations.
     *
     * @param array<string, string> $config [
     * 'dir' => (string),
     * 'url' => (string)
     * ]
     *
     * @return self
     */
    public function register(array $config): self;

    /**
     * Get asset url.
     *
     * @param string $file File  Relative path to the asset file in the "src"/"root" folder
     *
     * @return string
     */
    public function get(string $file): string;

    /**
     * Get the CSS url.
     *
     * @param string $entry Domain or file itself
     * @param string $file File  Relative path to the asset file in the "src"/"root" folder
     *
     * @return string
     */
    public function css(string $entry, string $file = ''): string;

    /**
     * Get the JS url.
     *
     * @param string $entry Domain or file itself
     * @param string $file File  Relative path to the asset file in the "src"/"root" folder
     *
     * @return string
     */
    public function js(string $entry, string $file = ''): string;

    /**
     * Get the image url.
     *
     * @param string $entry Domain or file itself
     * @param string $file File  Relative path to the asset file in the "src"/"root" folder
     *
     * @return string
     */
    public function image(string $entry, string $file = ''): string;

    /**
     * Get the SVG url.
     *
     * @param string $entry Domain or file itself
     * @param string $file File  Relative path to the asset file in the "src"/"root" folder
     *
     * @return string
     */
    public function svg(string $entry, string $file = ''): string;

    /**
     * Get the font url.
     *
     * @param string $entry Domain or file itself
     * @param string $file File  Relative path to the asset file in the "src"/"root" folder
     *
     * @return string
     */
    public function font(string $entry, string $file = ''): string;

    /**
     * The dir of the project.
     *
     * @return string
     */
    public function getDir(): string;

    /**
     * Get the project folder name
     *
     * @return string
     */
    public function getDirname(): string;

    /**
     * Get a unique project hook prefix based on the project folder.
     *
     * @return string
     */
    public function getHook(): string;

    /**
     * The root dir from the ViteJS config.
     *
     * @return string
     */
    public function getRoot(): string;

    /**
     * The out dir from the viteJS config.
     *
     * @return string
     */
    public function getOutDir(): string;

    /**
     * The entry from the viteJS config.
     *
     * @return string
     */
    public function getEntry(): string;

    /**
     * The full URL to the compiled files.
     *
     * @return string
     */
    public function __toString();
}

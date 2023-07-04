<?php

/**
 * Responsible for configuration and getting asset files through the manifest.json
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

/**
 * Class AssetsService
 */
class AssetsService implements AssetsInterface
{
    /**
     * Internal cache.
     *
     * @var array<string, mixed>
     */
    protected static array $cache = [];

    /**
     * The project URL.
     *
     * @var string
     */
    protected string $url;

    /**
     * The project dir path.
     *
     * @var string
     */
    protected string $dir;

    /**
     * The project hook prefix.
     *
     * @var string
     */
    protected string $hook;

    /**
     * The project out dir.
     *
     * @var string
     */
    protected string $outDir;

    /**
     * The project root.
     *
     * @var string
     */
    protected string $root;

    /**
     * The project entry.
     *
     * @var string
     */
    protected string $entry;

    /**
     * @inheritDoc
     */
    public function register(array $config): self
    {
        foreach (['dir', 'url', 'hook', 'root', 'outDir', 'entry'] as $key) {
            if (isset($config[$key])) {
                $this->{$key} = \untrailingslashit($config[$key]);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $file): string
    {
        try {
            $manifest = $this->getManifest($file);
        } catch (AssetsException $e) {
            if (\defined('WP_DEBUG') && \WP_DEBUG) {
                \wp_die(\esc_html($e->getMessage()));
            } else {
                if (\defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
                    \error_log(\esc_html($e->getMessage())); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }

                return ''; // A broken asset should not break the site in production.
            }
        }


        $url = !empty($manifest['file'])
            ? "{$this}/{$manifest['file']}"
            : '';

        /**
         * Filter the asset url.
         *
         * @param string $url The asset URL.
         * @param array<string, string|string[]|bool> $manifest The manifest data of the asset.
         * @param string $file Relative path to the asset file in the "src" folder.
         */
        return \apply_filters("assets_{$this->getHook()}_url", $url, $manifest, $file);
    }


    /**
     * @inheritDoc
     */
    public function css(string $entry, string $file = ''): string
    {
        if (!empty($file)) {
            return $this->get("{$entry}/{$this->getEntry()}/css/{$file}.css");
        } else {
            return $this->get("css/{$entry}.css");
        }
    }

    /**
     * @inheritDoc
     */
    public function js(string $entry, string $file = ''): string
    {
        if (!empty($file)) {
            return $this->get("{$entry}/{$this->getEntry()}/js/{$file}.js");
        } else {
            return $this->get("js/{$entry}.js");
        }
    }

    /**
     * @inheritDoc
     */
    public function image(string $entry, string $file = ''): string
    {
        if (!empty($file)) {
            return $this->get("{$entry}/{$this->getEntry()}/images/{$file}");
        } else {
            return $this->get("images/{$entry}");
        }
    }

    /**
     * @inheritDoc
     */
    public function svg(string $entry, string $file = ''): string
    {
        if (!empty($file)) {
            return $this->get("{$entry}/{$this->getEntry()}/svg/{$file}.svg");
        } else {
            return $this->get("svg/{$entry}.svg");
        }
    }

    /**
     * @inheritDoc
     */
    public function font(string $entry, string $file = ''): string
    {
        if (!empty($file)) {
            return $this->get("{$entry}/{$this->getEntry()}/fonts/{$file}");
        } else {
            return $this->get("fonts/{$entry}");
        }
    }

    /**
     * Check if manifest file exists and get manifest definitions.
     *
     * @param string $key Manifest key or empty to get the whole manifest array.
     *
     * @return string|array<string, string|string[]|bool|array<string, string|string[]|bool>>
     * @throws AssetsException
     */
    protected function getManifest(string $key = '')
    {
        if (!isset(static::$cache['manifest'])) {
            $path = "{$this->dir}/{$this->getOutDir()}";
            $file = "{$path}/manifest.json";

            if (!\is_readable($file)) {
                throw new AssetsException(\sprintf('[Vite] Manifest does not exist in %s.', $path));
            }

            $manifest = \json_decode(\file_get_contents($file), true); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

            if (\json_last_error()) {
                throw new AssetsException(\sprintf('[Vite] Manifest data from %s could not be decoded.', $file));
            }

            static::$cache['manifest'] = $manifest;
        }

        /**
         * Filter the manifest.
         *
         * @param array<string, string|string[]|bool|array<string, string|string[]|bool>> $manifest The manifest data.
         * @param string $key The key we're requesting.
         */
        static::$cache['manifest'] = \apply_filters("assets_{$this->getHook()}_manifest", static::$cache['manifest'], $key);

        if (empty($key)) {
            return static::$cache['manifest'];
        }

        if (!isset(static::$cache['manifest'][$key])) {
            throw new AssetsException(\sprintf('[Vite] %s does not exists inside the manifest.', $key));
        }

        return static::$cache['manifest'][$key];
    }

    /**
     * @inheritDoc
     */
    public function getDir(): string
    {
        return $this->dir ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getDirname(): string
    {
        if (!isset(static::$cache['dirname'])) {
            static::$cache['dirname'] = \basename($this->dir);
        }

        return static::$cache['dirname'];
    }

    /**
     * @inheritDoc
     */
    public function getHook(): string
    {
        if (isset($this->hook)) {
            return $this->hook;
        }

        if (!isset(static::$cache['hook'])) {
            static::$cache['hook'] = \str_replace('-', '_', $this->getDirname());
        }

        return static::$cache['hook'];
    }

    /**
     * @inheritDoc
     */
    public function getRoot(): string
    {
        return $this->root ?? 'src';
    }

    /**
     * @inheritDoc
     */
    public function getOutDir(): string
    {
        return $this->outDir ?? 'build';
    }

    /**
     * @inheritDoc
     */
    public function getEntry(): string
    {
        return $this->projectOutEntry ?? 'Static';
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return "{$this->url}/{$this->getOutDir()}";
    }
}

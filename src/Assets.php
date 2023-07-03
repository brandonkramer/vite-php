<?php

/**
 * The Assets' Facade.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

use RuntimeException;

/**
 * Class Assets
 *
 * @method static AssetsInterface get (string $file)
 * @method static AssetsInterface css (string $entry, string $file)
 * @method static AssetsInterface js (string $entry, string $file)
 * @method static AssetsInterface image (string $entry, string $file)
 * @method static AssetsInterface font (string $entry, string $file)
 * @method static AssetsInterface svg (string $entry, string $file)
 * @method static AssetsInterface getRoot ()
 * @method static AssetsInterface getOutDir ()
 * @method static AssetsInterface getEntry ()
 */
class Assets
{
    /**
     * The assets service.
     *
     * @var AssetsInterface
     */
    protected static AssetsInterface $assets;

    /**
     * Inject dependencies.
     *
     * @param AssetsInterface $assets
     */
    public function __construct(AssetsInterface $assets)
    {
        static::$assets = $assets;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array<string|int, mixed> $args
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function __callStatic(string $method, array $args): string
    {
        if (!isset(static::$assets)) {
            throw new RuntimeException('[Vite] Assets service has not been set.');
        }

        return static::$assets->{$method}(...$args);
    }

    /**
     * Get the dev server instance.
     *
     * @return DevServerInterface
     */
    public static function devServer(): DevServerInterface
    {
        return new DevServer(static::$assets);
    }
}

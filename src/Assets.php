<?php

/**
 * The Assets' Facade.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

/**
 * Class Assets
 *
 * @method static AssetsInterface register(array $config)
 * @method static AssetsInterface get(string $file = '')
 * @method static AssetsInterface css(string $entry, string $file = '')
 * @method static AssetsInterface js(string $entry, string $file = '')
 * @method static AssetsInterface image(string $entry, string $file = '')
 * @method static AssetsInterface font(string $entry, string $file = '')
 * @method static AssetsInterface svg(string $entry, string $file = '')
 * @method static AssetsInterface getRoot()
 * @method static AssetsInterface getOutDir()
 * @method static AssetsInterface getEntry()
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
     * Resolve the facade root instance.
     *
     * @return AssetsInterface
     */
    protected static function resolveInstance(): AssetsInterface
    {
        if (!isset(static::$assets)) {
            static::$assets = new AssetsService();
        }

        return static::$assets;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array<string|int, mixed> $args
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args)
    {
        static::resolveInstance();

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

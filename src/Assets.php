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
 * @method static AssetsServiceInterface get (string $file)
 * @method static AssetsServiceInterface css (string $entry, string $file)
 * @method static AssetsServiceInterface js (string $entry, string $file)
 * @method static AssetsServiceInterface image (string $entry, string $file)
 * @method static AssetsServiceInterface font (string $entry, string $file)
 * @method static AssetsServiceInterface svg (string $entry, string $file)
 * @method static AssetsServiceInterface getRoot ()
 * @method static AssetsServiceInterface getOutDir ()
 * @method static AssetsServiceInterface getEntry ()
 */
class Assets
{
    /**
     * The assets service.
     *
     * @var AssetsServiceInterface
     */
    protected static AssetsServiceInterface $assetsService;

    /**
     * Inject dependencies.
     *
     * @param AssetsServiceInterface $assetsService
     */
    public function __construct(AssetsServiceInterface $assetsService)
    {
        static::$assetsService = $assetsService;
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
        if (!isset(static::$assetsService)) {
            throw new RuntimeException('[Vite] Assets service has not been set.');
        }

        return static::$assetsService->{$method}(...$args);
    }

    /**
     * Get the dev server instance.
     *
     * @return DevServerInterface
     */
    public static function devServer(): DevServerInterface
    {
        return new DevServer(static::$assetsService);
    }
}

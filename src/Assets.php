<?php

/**
 * The Assets' Facade.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

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
     * The Assets.
     *
     * @var AssetsInterface
     */
    protected static AssetsInterface $assets;

    /**
     * The Dev Server.
     *
     * @var DevServerInterface
     */
    protected static DevServerInterface $devServer;

    /**
     * PSR Container.
     *
     * @var ContainerInterface
     */
    protected static ContainerInterface $container;

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array<string|int, mixed> $args
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::resolveInstance();

        if (!isset($instance)) {
            throw new RuntimeException('[Vite] Assets service could not be resolved.');
        }

        return $instance->{$method}(...$args);
    }

    /**
     * Resolve the facade instance.
     *
     * @return AssetsInterface|null
     */
    protected static function resolveInstance(): ?AssetsInterface
    {
        if (!isset(static::$assets)) {
            if (isset(static::$container)) {
                if (static::$container->has(AssetsInterface::class)) {
                    static::$assets = static::resolveFacadeInstance(AssetsInterface::class);
                }
                if (static::$container->has(DevServerInterface::class)) {
                    static::$devServer = static::resolveFacadeInstance(DevServerInterface::class);
                }
            } else {
                static::$assets = new AssetsService();
                static::$devServer = new DevServer(static::$assets);
            }
        }

        return static::$assets;
    }

    /**
     * Get the registered class from the container.
     *
     * @param string $id
     *
     * @return mixed|void
     */
    protected static function resolveFacadeInstance(string $id)
    {
        try {
            return static::$container->get($id);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            if (\defined('WP_DEBUG') && \WP_DEBUG) {
                \wp_die(\esc_html($e->getMessage()));
            } else {
                if (\defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
                    \error_log(\esc_html($e->getMessage())); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }
            }
        }
    }

    /**
     * Set facade(s).
     *
     * @param ContainerInterface | AssetsInterface | DevServerInterface ...$instances
     *
     * @return void
     */
    public static function setFacade(...$instances)
    {
        foreach ($instances as $instance) {
            if ($instance instanceof ContainerInterface) {
                static::$container = $instance;
            } elseif ($instance instanceof AssetsInterface) {
                static::$assets = $instance;
            } elseif ($instance instanceof DevServerInterface) {
                static::$devServer = $instance;
            }
        }
    }

    /**
     * Get the dev server instance.
     *
     * @return DevServerInterface
     */
    public static function devServer(): DevServerInterface
    {
        return static::$devServer;
    }
}

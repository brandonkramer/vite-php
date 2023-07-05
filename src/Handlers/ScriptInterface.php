<?php

/**
 * Contract for the Script Service.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite\Handlers;

/**
 * Class ScriptInterface
 */
interface ScriptInterface
{
    /**
     * Prepend inline script.
     *
     * @param string $code Javascript code
     *
     * @return $this
     */
    public function prependInline(string $code): self;

    /**
     * Append inline script.
     *
     * @param string $code Javascript code
     *
     * @return $this
     */
    public function appendInline(string $code): self;

    /**
     * Localize script.
     *
     * @param string $objectName
     * @param array<string|int, mixed> $data
     *
     * @return $this
     */
    public function localize(string $objectName, array $data): self;

    /**
     * Add async attribute.
     *
     * @return $this
     */
    public function useAsync(): self;

    /**
     * Add defer attribute.
     *
     * @return $this
     */
    public function useDefer(): self;

    /**
     * Add custom attribute.
     *
     * @return $this
     */
    public function useAttribute(string $name, string $value): self;
}

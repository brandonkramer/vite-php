<?php

/**
 * Contract for the Style Service.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite\Handlers;

/**
 * Class StyleInterface
 */
interface StyleInterface
{
    /**
     * Set alt.
     *
     * @return $this
     */
    public function asAlternate(): self;

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function withTitle(string $title): self;

    /**
     * Append inline script.
     *
     * @param string $code Javascript code
     *
     * @return $this
     */
    public function appendInline(string $code): self;

    /**
     * Add custom attribute.
     *
     * @return $this
     */
    public function useAttribute(string $name, string $value): self;
}

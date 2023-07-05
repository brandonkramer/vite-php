<?php

/**
 * Responsible for providing style functions.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite;

/**
 * Class StyleService
 */
class StyleService implements StyleInterface
{
    /**
     * Script handle.
     *
     * @var string
     */
    private string $handle;

    /**
     * Set handle.
     */
    public function __construct(string $handle)
    {
        $this->handle = $handle;
    }

    /**
     * @inheritDoc
     */
    public function asAlternate(): self
    {
        if ((\wp_styles()->add_data($this->handle, 'alt', true)) === false) {
            $error = \sprintf(
            /* translators: %1$s will be replaced with a style handle name. */
                \esc_html__('Failed to add alternate for: %s.'),
                $this->handle
            );
            if (\defined('WP_DEBUG') && \WP_DEBUG) {
                \wp_die(\esc_html($error));
            } else {
                if (\defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
                    \error_log(\esc_html($error)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withTitle(string $title): self
    {
        if ((\wp_styles()->add_data($this->handle, 'title', $title)) === false) {
            $error = \sprintf(
            /* translators: %1$s will be replaced with a style handle name. */
                \esc_html__('Failed to add title for: %s.'),
                $this->handle
            );
            if (\defined('WP_DEBUG') && \WP_DEBUG) {
                \wp_die(\esc_html($error));
            } else {
                if (\defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
                    \error_log(\esc_html($error)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function appendInline(string $code): self
    {
        if ((\wp_add_inline_style($this->handle, $code)) === false) {
            $error = \sprintf(
            /* translators: %1$s will be replaced with a style handle name. */
                \esc_html__('Failed to append inline for: %s.'),
                $this->handle
            );
            if (\defined('WP_DEBUG') && \WP_DEBUG) {
                \wp_die(\esc_html($error));
            } else {
                if (\defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
                    \error_log(\esc_html($error)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function useAttribute(string $name, string $value): self
    {
        $this->setAttribute($name, $value);

        return $this;
    }

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    private function setAttribute(string $name, string $value = '')
    {
        \add_filter('style_loader_tag', function ($tag, $handle) use ($name, $value) {
            $attribute = empty($value) ? $name : "{$name}=\"{$value}\"";

            if ($handle === $this->handle) {
                return \str_replace('<link ', "<link {$attribute} ", $tag);
            }

            return $tag;
        }, 10, 2);
    }
}

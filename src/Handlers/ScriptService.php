<?php

/**
 * Responsible for providing script functions.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace WPStrap\Vite\Handlers;

/**
 * Class ScriptService
 */
class ScriptService implements ScriptInterface
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
    public function prependInline(string $code): self
    {
        if ((\wp_scripts()->add_data($this->handle, 'before', $code)) === false) {
            $error = \sprintf(
            /* translators: %1$s will be replaced with a script handle name. */
                \esc_html__('Failed to prepend inline script for: %s.'),
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
        if ((\wp_scripts()->add_data($this->handle, 'after', $code)) === false) {
            $error = \sprintf(
            /* translators: %1$s will be replaced with a script handle name. */
                \esc_html__('Failed to append inline script for: %s.'),
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
    public function localize(string $objectName, array $data): self
    {
        if ((\wp_localize_script($this->handle, $objectName, $data)) === false) {
            $error = \sprintf(
            /* translators: %1$s will be replaced with a script handle name. */
                \esc_html__('Failed to localize script for: %s.'),
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
    public function useAsync(): self
    {
        $this->setAttribute('async');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function useDefer(): self
    {
        $this->setAttribute('defer');

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
        \add_filter('script_loader_tag', function ($tag, $handle) use ($name, $value) {
            $attribute = empty($value) ? $name : "{$name}=\"{$value}\"";

            if ($handle === $this->handle) {
                return \str_replace('<script ', "<script {$attribute} ", $tag);
            }

            return $tag;
        }, 10, 2);
    }
}

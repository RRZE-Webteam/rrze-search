<?php

namespace RRZE\RRZESearch\Infrastructure;

defined('ABSPATH') || exit;

/**
 * Enforces the network-wide RRZE Search usage limits when present.
 *
 * Provides helpers to check whether a request may be performed, to describe
 * exceeded periods, and to dispatch the increment hook that the network
 * settings plugin listens to.
 */
final class UsageLimiter
{
    private const OPTION_NAME = 'rrze_search_network_limits_and_stats';

    private const PERIODS = ['hour', 'day', 'week', 'month', 'year'];

    private const TRANSIENT_KEY = 'rrze_search_limit_block';

    /**
     * Wire the limit enforcement into rrze_search_increment handling.
     */
    public function register(): void
    {
        add_filter('rrze_search_should_increment', [$this, 'enforceIncrementLimits'], 10, 2);
        add_action('admin_notices', [$this, 'renderAdminNotice']);
        add_action('network_admin_notices', [$this, 'renderAdminNotice']);
    }

    /**
     * Determines whether another RRZE Search request may be consumed.
     *
     * @return array{0: bool, 1: array<string, array<string,int>>} Tuple of
     *         [isAllowed, exceededPeriods]. The second element contains any
     *         periods that are already at or above their limit.
     */
    public static function canConsumeRequest(): array
    {
        if (!is_multisite()) {
            return [true, []];
        }

        $option = get_site_option(self::OPTION_NAME, null);

        if (!is_array($option)) {
            self::clearBlockedState();
            return [true, []];
        }

        $limits = self::normalizePeriods($option['limits'] ?? []);
        $totals = self::normalizePeriods($option['totals'] ?? []);

        $exceeded = [];

        foreach (self::PERIODS as $period) {
            $limit = $limits[$period] ?? 0;
            if ($limit <= 0) {
                continue;
            }

            $total = $totals[$period] ?? 0;
            if ($total >= $limit) {
                $exceeded[$period] = [
                    'limit' => $limit,
                    'total' => $total,
                ];
            }
        }

        if (!empty($exceeded)) {
            self::storeBlockedState($exceeded);
            return [false, $exceeded];
        }

        self::clearBlockedState();
        return [true, []];
    }

    /**
     * Formats the exceeded periods for display in error messages.
     *
     * @param array<string, array<string,int>> $exceeded
     */
    public static function describeExceededPeriods(array $exceeded): string
    {
        if ($exceeded === []) {
            return '';
        }

        $labels = [
            'hour'  => __('hourly', 'rrze-search'),
            'day'   => __('daily', 'rrze-search'),
            'week'  => __('weekly', 'rrze-search'),
            'month' => __('monthly', 'rrze-search'),
            'year'  => __('yearly', 'rrze-search'),
        ];

        $parts = [];
        foreach ($exceeded as $period => $stats) {
            $label = $labels[$period] ?? $period;
            $limit = (int) ($stats['limit'] ?? 0);
            $total = (int) ($stats['total'] ?? 0);
            $parts[] = sprintf('%s %d/%d', $label, $total, $limit);
        }

        return implode(', ', $parts);
    }

    /**
     * Dispatches the increment hook so the network plugin can count usage.
     *
     * @param array<string,mixed> $context Optional metadata about the request.
     */
    public static function recordIncrement(array $context = []): void
    {
        do_action('rrze_search_increment', $context);
    }

    /**
     * Whether engine selection widgets should be hidden due to a temporary block.
     */
    public static function shouldForceFallback(): bool
    {
        return !empty(self::getBlockedState());
    }

    /**
     * Returns metadata about the current block, if any.
     *
     * @return array<string,mixed>
     */
    public static function getBlockedMetadata(): array
    {
        $state = self::getBlockedState();
        return isset($state['exceeded']) && is_array($state['exceeded'])
            ? $state['exceeded']
            : [];
    }

    /**
     * Filter callback that blocks increments when limits are reached.
     *
     * @param bool  $allowed Current allowance passed through the filter chain.
     * @param array $context Metadata supplied by the increment caller.
     */
    public function enforceIncrementLimits(bool $allowed, array $context): bool
    {
        if (!$allowed) {
            return false;
        }

        [$canConsume] = self::canConsumeRequest();
        return $canConsume;
    }

    /**
     * Display an admin notice when RRZE Search limits prevent remote queries.
     */
    public function renderAdminNotice(): void
    {
        if ((function_exists('wp_doing_ajax') && wp_doing_ajax()) || !is_user_logged_in()) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        [$allowed, $exceeded] = self::canConsumeRequest();
        if ($allowed || empty($exceeded)) {
            return;
        }

        $summary = self::describeExceededPeriods($exceeded);
        $message = __('RRZE Search usage limit reached. Remote search will fall back to the local search until quotas reset.', 'rrze-search');
        if ($summary !== '') {
            $message = sprintf(
                __('RRZE Search usage limit reached (%s). Remote search will fall back to the local search until quotas reset.', 'rrze-search'),
                $summary
            );
        }

        printf('<div class="notice notice-warning"><p>%s</p></div>', esc_html($message));
    }

    /**
     * @param mixed $values
     *
     * @return array<string,int>
     */
    private static function normalizePeriods($values): array
    {
        if (!is_array($values)) {
            return [];
        }

        $normalized = [];
        foreach (self::PERIODS as $period) {
            if (isset($values[$period])) {
                $normalized[$period] = max(0, (int) $values[$period]);
            }
        }

        return $normalized;
    }

    /**
     * Persist a short-lived block state when limits are hit.
     *
     * @param array<string,array<string,int>> $exceeded
     */
    private static function storeBlockedState(array $exceeded): void
    {
        $payload = [
            'blocked'  => true,
            'exceeded' => $exceeded,
            'stored'   => time(),
        ];

        $ttl = HOUR_IN_SECONDS;

        if (is_multisite()) {
            set_site_transient(self::TRANSIENT_KEY, $payload, $ttl);
        } else {
            set_transient(self::TRANSIENT_KEY, $payload, $ttl);
        }
    }

    /**
     * Remove any cached block state when limits allow consumption again.
     */
    private static function clearBlockedState(): void
    {
        if (is_multisite()) {
            delete_site_transient(self::TRANSIENT_KEY);
        } else {
            delete_transient(self::TRANSIENT_KEY);
        }
    }

    /**
     * @return array<string,mixed>
     */
    private static function getBlockedState(): array
    {
        $value = is_multisite()
            ? get_site_transient(self::TRANSIENT_KEY)
            : get_transient(self::TRANSIENT_KEY);

        return is_array($value) ? $value : [];
    }
}

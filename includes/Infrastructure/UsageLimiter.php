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

    /**
     * Wire the limit enforcement into rrze_search_increment handling.
     */
    public function register(): void
    {
        add_filter('rrze_search_should_increment', [$this, 'enforceIncrementLimits'], 10, 2);
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

        return [empty($exceeded), $exceeded];
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
}


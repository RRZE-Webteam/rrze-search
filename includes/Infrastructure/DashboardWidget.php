<?php

namespace RRZE\RRZESearch\Infrastructure;

defined('ABSPATH') || exit;

/**
 * Renders a dashboard widget that summarizes the remaining RRZE Search usage.
 */
class DashboardWidget
{
    private const CONTAINER_ID = 'rrze-search-dashboard-widget';
    private static $instanceCounter = 0;

    /** @var array<string,string> */
    private const PERIOD_LABELS = [
        'day'   => 'Daily',
        'week'  => 'Weekly',
        'month' => 'Monthly',
        'year'  => 'Yearly',
    ];

    /**
     * Hooks the widget into both the site and network dashboards.
     */
    public function register(): void
    {
        add_action('wp_dashboard_setup', [$this, 'registerWidget']);
        add_action('wp_network_dashboard_setup', [$this, 'registerWidget']);
    }

    /**
     * Adds the dashboard widget for users that can manage the plugin settings.
     */
    public function registerWidget(): void
    {
        if (!$this->currentUserCanView()) {
            return;
        }

        wp_add_dashboard_widget(
            'rrze_search_usage_widget',
            __('RRZE Search API Usage', 'rrze-search'),
            [$this, 'renderWidget']
        );
    }

    /**
     * Prints the React mount point that will host the D3 pie chart.
     */
    public function renderWidget(): void
    {
        $dataset = $this->getUsageDataset();
        $emptyState = __('Usage data is not available at the moment.', 'rrze-search');

        $usedLabel = __('Used quota', 'rrze-search');
        $availableLabel = __('Available quota', 'rrze-search');
        $selectLabel = __('Quota period', 'rrze-search');
        $description = __('Remaining RRZE Search API requests before the quota resets.', 'rrze-search');

        $defaultPeriod = $this->getDefaultPeriod($dataset);
        $containerId = sprintf('%s-%d', self::CONTAINER_ID, ++self::$instanceCounter);
        $chartId = $containerId . '-chart';
        $selectId = $containerId . '-period';

        if ($dataset === []) {
            printf('<div class="rrze-search-dashboard-widget" data-empty="%1$s">%1$s</div>', esc_html($emptyState));
            return;
        }

        echo '<div class="rrze-search-dashboard-widget"'
            . ' data-usage="' . esc_attr(wp_json_encode($dataset)) . '"'
            . ' data-default-period="' . esc_attr($defaultPeriod) . '"'
            . ' data-empty="' . esc_attr($emptyState) . '"'
            . ' data-used-label="' . esc_attr($usedLabel) . '"'
            . ' data-available-label="' . esc_attr($availableLabel) . '"'
            . ' data-description="' . esc_attr($description) . '">';

        echo '<div class="rrze-search-dashboard-widget__controls">';
        printf('<label for="%1$s">%2$s</label>', esc_attr($selectId), esc_html($selectLabel));
        echo '<select id="' . esc_attr($selectId) . '" class="rrze-search-dashboard-widget__period">';
        foreach ($dataset as $period => $details) {
            printf(
                '<option value="%1$s" %3$s>%2$s</option>',
                esc_attr($period),
                esc_html($details['label']),
                selected($period, $defaultPeriod, false)
            );
        }
        echo '</select></div>';

        printf('<div id="%1$s" class="rrze-search-dashboard-widget__chart"></div>', esc_attr($chartId));
        echo '</div>';
    }

    /**
     * Builds the usage dataset keyed by period.
     *
     * @return array<string, array<string,int|string>>
     */
    private function getUsageDataset(): array
    {
        $snapshot = UsageLimiter::getUsageSnapshot();
        $dataset = [];

        foreach (self::PERIOD_LABELS as $period => $label) {
            $limit = (int) ($snapshot[$period]['limit'] ?? 0);
            $total = (int) ($snapshot[$period]['total'] ?? 0);

            if ($limit <= 0 && $total <= 0) {
                continue;
            }

            $dataset[$period] = [
                'label'     => sprintf(__('%s quota', 'rrze-search'), __($label, 'rrze-search')),
                'limit'     => max($limit, 0),
                'total'     => max($total, 0),
                'remaining' => $limit > 0 ? max($limit - $total, 0) : 0,
            ];
        }

        return $dataset;
    }

    /**
     * Default period for the widget selector.
     */
    private function getDefaultPeriod(array $dataset): string
    {
        if (isset($dataset['day'])) {
            return 'day';
        }

        $keys = array_keys($dataset);
        return $keys[0] ?? 'day';
    }

    /**
     * Whether the current user should see the widget on the dashboard.
     */
    private function currentUserCanView(): bool
    {
        if (is_multisite() && is_network_admin()) {
            return current_user_can('manage_network_options');
        }

        return current_user_can('manage_options');
    }
}

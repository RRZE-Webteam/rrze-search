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
        $description = __('Remaining RRZE Search API requests before the quota resets.', 'rrze-search');

        $containerId = sprintf('%s-%d', self::CONTAINER_ID, ++self::$instanceCounter);

        if ($dataset === []) {
            printf('<div class="rrze-search-dashboard-widget" data-empty="%1$s">%1$s</div>', esc_html($emptyState));
            return;
        }

        echo '<div class="rrze-search-dashboard-widget"'
            . ' data-usage="' . esc_attr(wp_json_encode($dataset)) . '"'
            . ' data-empty="' . esc_attr($emptyState) . '"'
            . ' data-used-label="' . esc_attr($usedLabel) . '"'
            . ' data-available-label="' . esc_attr($availableLabel) . '"'
            . ' data-description="' . esc_attr($description) . '">';

        echo '<div class="rrze-search-dashboard-widget__charts">';
        foreach ($dataset as $period => $details) {
            $chartId = sprintf('%s-%s-chart', $containerId, $period);

            echo '<div class="rrze-search-dashboard-widget__chart-card">'
                . '<h4 class="rrze-search-dashboard-widget__chart-title">' . esc_html($details['label']) . '</h4>'
                . '<div id="' . esc_attr($chartId) . '"'
                . ' class="rrze-search-dashboard-widget__chart"'
                . ' data-period="' . esc_attr($period) . '"></div>'
                . '</div>';
        }
        echo '</div></div>';
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

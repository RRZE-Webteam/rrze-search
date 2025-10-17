<?php
defined('ABSPATH') || exit;

/**
 * Mask a secret so the rendered string keeps the original length:
 * - All leading characters are replaced with '*'
 * - Only the last $visible characters are shown
 * - If the secret length <= $visible, the entire value is shown (per request)
 */
$mask_tail_exact_length = static function (?string $value, int $visible = 5): string {
    $value = (string) $value;
    if ($value === '') {
        return '—';
    }

    $len = function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    $visible = min($visible, $len);
    $maskedLen = $len - $visible;

    $tail = function_exists('mb_substr')
        ? mb_substr($value, $len - $visible, $visible, 'UTF-8')
        : substr($value, -$visible);

    return str_repeat('*', $maskedLen) . $tail;
};

$engines = is_array($globalEngines) ? $globalEngines : [];

if (empty($engines)) : ?>
    <p><?php echo esc_html__('No global preset search engines are configured.', 'rrze-search'); ?></p>
    <?php
    return;
endif;

// Normalize to an iterable list of ['name'=>string, 'desc'=>string, 'cx'=>string, 'api'=>string]
$normalized = [];

foreach ($engines as $ek => $ev) {
    if (!is_array($ev)) {
        continue;
    }

    $name = isset($ev['name']) ? (string) $ev['name'] : (is_string($ek) ? (string) $ek : '');
    $desc = isset($ev['desc']) ? (string) $ev['desc'] : '';
    $cx   = isset($ev['cx'])   ? (string) $ev['cx']   : '';
    $api  = isset($ev['api'])  ? (string) $ev['api']  : '';

    $normalized[] = [
        'name' => $name !== '' ? $name : __('(Unnamed engine)', 'rrze-search'),
        'desc' => $desc,
        'cx'   => $cx,
        'api'  => $api,
    ];
}

// Sort by name for deterministic display
usort($normalized, static function ($a, $b) {
    return strcasecmp($a['name'], $b['name']);
});
?>
<div class="rrze-presets-wrap">
    <h3><?php echo esc_html__('Global Preset Search Engines', 'rrze-search'); ?></h3>
    <p class="description">
        <?php echo esc_html__('These engines are globally configured by an Superadministrator and shown here for reference. Sensitive values are masked.', 'rrze-search'); ?>
    </p>

    <table class="widefat striped rrze-presets-table" role="table">
        <thead>
        <tr>
            <th scope="col"><?php echo esc_html__('Name', 'rrze-search'); ?></th>
            <th scope="col"><?php echo esc_html__('Description', 'rrze-search'); ?></th>
            <th scope="col"><?php echo esc_html__('CX (Search Engine)', 'rrze-search'); ?></th>
            <th scope="col"><?php echo esc_html__('API Key', 'rrze-search'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($normalized as $engine) : ?>
            <tr>
                <td><?php echo esc_html($engine['name']); ?></td>
                <td><?php echo esc_html($engine['desc']); ?></td>
                <td><code><?php echo esc_html($mask_tail_exact_length($engine['cx'])); ?></code></td>
                <td><code><?php echo esc_html($mask_tail_exact_length($engine['api'])); ?></code></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    .rrze-presets-wrap { margin-top: 8px; }
    .rrze-presets-table code { background: transparent; }
</style>

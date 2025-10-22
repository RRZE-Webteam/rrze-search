<?php defined( 'ABSPATH' ) || exit; ?>
<?php settings_errors(); ?>

<?php if (!empty($hasGlobalPresets)) : ?>
    <p class="description">
        <?php echo esc_html__('Network-wide search engines are available. You can still adjust which ones are enabled locally and choose the default fallback below.', 'rrze-search'); ?>
    </p>
<?php endif; ?>

<table id="rrze_search_resource_form" class="form-table">
    <?php
    $nextEngineIndex = 0;
    $defaultChoices = [];
    foreach ($engines as $resourceEngine) {
        $rowColor  = ($nextEngineIndex % 2) ? '#ddd' : '#bbb';
        $engineEnabled = isset($resourceEngine['enabled']) ? (bool) $resourceEngine['enabled'] : false;
        $isEnabled = $engineEnabled ? 'checked ' : '';
        $resourceId = isset($resourceEngine['resource_id']) && $resourceEngine['resource_id'] !== ''
            ? (string) $resourceEngine['resource_id']
            : (string) $nextEngineIndex;
        $label = sprintf($resourceEngine['resource_name'], '');
        $defaultChoices[] = [
            'resource_id' => $resourceId,
            'label'       => $label,
            'enabled'     => $engineEnabled,
        ];
        ?>
        <tr bgcolor="<?php echo $rowColor; ?>">
            <td>
                <label>
                    <input type="hidden" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][resource_id]"
                           value="<?= $resourceEngine['resource_id']; ?>">
                    <input type="hidden" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][resource_name]"
                           value="<?= $resourceEngine['resource_name']; ?>">
                    <input type="hidden" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][resource_class]"
                           value="<?= $resourceEngine['resource_class']; ?>">
                    <input type="checkbox" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][enabled]" <?= $isEnabled; ?>>
                    <?= sprintf($resourceEngine['resource_name'], ''); ?>
                </label>
            </td>
            <td>
                <label class="resource_table_label">
                    <span><?php _e('Privacy Policy / Instruction Page ID', 'rrze-search'); ?></span>
                    <input type="number" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][resource_disclaimer]"
                           value="<?= $resourceEngine['resource_disclaimer']; ?>">
                </label>
                <?php // } ?>
            </td>
        </tr>
        <?php
        ++$nextEngineIndex;
    }
    ?>
</table>

<?php if (!empty($defaultChoices)) : ?>
    <fieldset class="rrze-search-default-engine">
        <legend><?php echo esc_html__('Default search engine', 'rrze-search'); ?></legend>
        <p class="description">
            <?php echo esc_html__('Choose which enabled engine should be preselected for site-wide search forms. If the selected engine becomes unavailable, the plugin will fall back to the local WordPress search.', 'rrze-search'); ?>
        </p>
        <select name="<?= $optionName ?>[rrze_search_default_engine]" class="widefat">
            <?php foreach ($defaultChoices as $choice) :
                $optionLabel = $choice['label'];
                if (!$choice['enabled']) {
                    $optionLabel .= ' ' . sprintf('(%s)', esc_html__('disabled', 'rrze-search'));
                }
                ?>
                <option value="<?= esc_attr($choice['resource_id']); ?>"
                    <?php selected($defaultEngine, $choice['resource_id']); ?>
                    <?php disabled(!$choice['enabled']); ?>>
                    <?= esc_html($optionLabel); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </fieldset>
<?php endif; ?>

<style>
    .rrze-search-default-engine {
        margin-top: 1.5em;
    }
    .rrze-search-default-engine select.widefat {
        max-width: 360px;
    }
</style>

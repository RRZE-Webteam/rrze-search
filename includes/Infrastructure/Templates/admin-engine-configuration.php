<?php
$nextResourceIndex = 0;
if ($resources === 'empty' || !is_array($resources)) {
    $resources = [];
}

foreach ($resources as $resource) {
    // Defensive Defaults
    $resource        = is_array($resource) ? $resource : [];
    $resource_id_key = 'resource_id';
    $resource_class  = isset($resource['resource_class']) ? (string) $resource['resource_class'] : '';
    $resource_name   = isset($resource['resource_name']) ? (string) $resource['resource_name'] : '';
    $resource_args   = isset($resource['args']) && is_array($resource['args']) ? $resource['args'] : [];

    // Unique ID erzeugen (persistieren falls vorhanden)
    $uId = !empty($resource[$resource_id_key]) ? (string) $resource[$resource_id_key] : uniqid('rrze_', true);

    // Zeilenfarbe (besser per CSS-Klasse, hier beibehalten falls gewünscht)
    $rowColor = ($nextResourceIndex % 2) ? '#ddd' : '#bbb';

    // Engines-Collection defensiv lesen
    $engines = is_array($this->enginesClassCollection ?? null) ? $this->enginesClassCollection : [];
    $engineVars = isset($engines[$resource_class]['variables']) && is_array($engines[$resource_class]['variables'])
        ? $engines[$resource_class]['variables']
        : [];
    ?>
    <!-- Persistente Resource-ID -->
    <input type="hidden"
           class="regular-text"
           id="<?php echo esc_attr($fieldName . '_' . $nextResourceIndex . '_id'); ?>"
           name="<?php echo esc_attr($optionName . '[' . $fieldName . '][' . $nextResourceIndex . '][' . $resource_id_key . ']'); ?>"
           value="<?php echo esc_attr($uId); ?>" />

    <tr style="background-color: <?php echo esc_attr($rowColor); ?>;">
        <td style="vertical-align:top">
            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo esc_html(__('Type', 'rrze-search')); ?></span>
                    <select
                            id="<?php echo esc_attr($fieldName . '_' . $nextResourceIndex . '_class'); ?>"
                            name="<?php echo esc_attr($optionName . '[' . $fieldName . '][' . $nextResourceIndex . '][resource_class]'); ?>"
                            class="regular-text">
                        <?php foreach ($engines as $key => $value): ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected($key, $resource_class); ?>>
                                <?php echo esc_html($value['name'] ?? $key); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </fieldset>

            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo esc_html(__('Label', 'rrze-search')); ?></span>
                    <input type="text"
                           class="regular-text"
                           id="<?php echo esc_attr($fieldName . '_' . $nextResourceIndex . '_name'); ?>"
                           name="<?php echo esc_attr($optionName . '[' . $fieldName . '][' . $nextResourceIndex . '][resource_name]'); ?>"
                           value="<?php echo esc_attr($resource_name); ?>" />
                </label>
            </fieldset>
        </td>

        <td style="vertical-align:top">
            <?php if (!empty($engineVars)): ?>
                <?php foreach ($engineVars as $index => $engineVariable):
                    $varKey = (string) $engineVariable;
                    $preval = isset($resource_args[$varKey]) ? (string) $resource_args[$varKey] : '';
                    ?>
                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo esc_html(strtoupper($varKey)); ?></span>
                            <input type="text"
                                   class="regular-text"
                                   id="<?php echo esc_attr($fieldName . '_' . $nextResourceIndex . '_arg_' . $varKey); ?>"
                                   name="<?php echo esc_attr($optionName . '[' . $fieldName . '][' . $nextResourceIndex . '][args][' . $varKey . ']'); ?>"
                                   value="<?php echo esc_attr($preval); ?>" />
                        </label>
                    </fieldset>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>

        <td>
            <a href="javascript:rrze_resource_removal(<?php echo esc_js((int) $nextResourceIndex); ?>)"
               class="button button-primary">
                <?php echo esc_html(__('Remove', 'rrze-search')); ?>
            </a>
        </td>
    </tr>
    <?php
    $nextResourceIndex++;
}
?>

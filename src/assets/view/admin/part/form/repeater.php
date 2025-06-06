<?php
/**
 * @var string $name
 * @var string $label
 * @var array $fields
 * @var array $value
 */

?>
<div class="form__field-row form__field-row_<?= $name;?>">
    <?php if (isset($label) && $label) { ?>
        <div class="form__field-label field__field-label_<?= $name;?>">
            <label for="<?= $name;?>"><?= $label;?></label>
        </div>
    <?php } ?>
    <div class="form__field form__field_repeater">
        <table class="repeater-items">
            <?php if (!empty($value)) { ?>
                <?php foreach ($value as $index => $item) { ?>
                    <tr>
                        <?php foreach ($fields as $field) { ?>
                            <?php $fieldValue = $item[$field["name"]] ?? ''; ?>
                            <td>
                                <?= MVCTheme::printField([
                                    "type" => $field["type"],
                                    "name" => $name . '[' . $index . '][' . $field["name"] . ']',
                                    "title" => $field["title"],
                                    "options" => $field["options"] ?? [],
                                    "value" => $fieldValue
                                ], $fieldValue); ?>
                            </td>
                        <?php } ?>
                        <td>
                            <button type="button" class="button remove-repeater-item">Удалить</button>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>
        <!-- Скрытый шаблон для новых элементов -->
        <table style="display: none;">
            <tr class="repeater-item-template">
                <?php foreach ($fields as $field) { ?>
                    <td>
                        <?= MVCTheme::printField([
                            "type" => $field["type"],
                            "name" => $name . '[__index__][' . $field["name"] . ']',
                            "title" => $field["title"],
                            "value" => ""
                        ], ""); ?>
                    </td>
                <?php } ?>
                <td>
                    <button type="button" class="button remove-repeater-item">Удалить</button>
                </td>
            </tr>
        </table>
        <button type="button" class="button add-repeater-item">Добавить элемент</button>
    </div>
</div>
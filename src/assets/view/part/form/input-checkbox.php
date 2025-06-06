<?php
/**
 * @var string $type
 * @var string $name
 * @var string $label
 * @var bool $required
 * @var string $value
 * @var string $class
 * @var string $placeholder
 * @var string $disabled
 */
?>
<?php if ( isset($name) && $name && isset($label) ) { ?>
    <div class="form__field-row form__field-row_<?= $name;?>">
        <label class="form__field__checkbox-label" for="<?= $name;?>">
            <input type="checkbox" id="<?= $name;?>" name="<?= $name;?>" value="<?= $value ?? "";?>" class="form__field__checkbox-input form__field__checkbox-input_<?= $name;?> <?= ($class ?? "") ?>" placeholder="<?= $placeholder ?? "" ?>" <?= (isset($required) && $required  ? "required" : "");?> <?= (isset($disabled) && $disabled  ? "disabled" : "");?>>
            <div class="form__field__checkbox-box"></div>
            <span class="form__field__checkbox-name"><?= $label;?></span>
        </label>
    </div>
<?php } else { ?>
    Укажите имя поля
<?php } ?>
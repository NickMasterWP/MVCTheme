<?php
/**
 * @var string $type
 * @var string $name
 * @var string $label
 * @var string $class
 * @var string $disabled
 * @var string $value
 */
?>
<?php if ( isset($type) && isset($name) ) { ?>
    <div class="form__field-row form__field-row_<?= $name;?>">
        <div class="form__field form__field_<?= $type;?> ">
            <button type="<?= $type ?? "button" ?>" id="button-<?= $name;?>" name="<?= $name;?>" value="<?= $value ?? ""?>"
                    class="form__control form__control_button form__control_<?= $type;?> form__control_<?= $name;?> <?= ($class ?? "") ?>" <?= (isset($disabled) && $disabled  ? "disabled" : "");?>><?= $label;?></button>
        </div>
    </div>
<?php } ?>

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
<?php if ( isset($type) && isset($name) ) { ?>
    <div class="form__field-row form__field-row_<?= $name;?>">
        <?php if ( isset($label) && $label ) { ?>
            <div class="form__field-label  field__field-label_<?= $name;?>">
                <label for="<?= $name;?>"><?= $label;?></label>
                <?php if  (isset($required) && $required ) {?>
                    <span class="form__field-sup">*</span>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="form__field form__field_<?= $type;?> ">
            <input type="<?= $type;?>" id="<?= $name;?>" name="<?= $name;?>" value="<?= $value ?? "";?>" class="form__control form__control_<?= $type;?> form__control_<?= $name;?> <?= ($class ?? "") ?>" placeholder="<?= $placeholder ?? "" ?>" <?= (isset($required) && $required  ? "required" : "");?> <?= (isset($disabled) && $disabled  ? "disabled" : "");?>>
        </div>
    </div>
<?php } ?>
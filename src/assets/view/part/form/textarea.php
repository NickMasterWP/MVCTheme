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
<?php if ( isset($name) ) { ?>
    <div class="form__field-row form__field-row_<?= $name;?>">
        <?php if ( isset($label) && $label ) { ?>
            <div class="form__field-label  field__field-label_<?= $name;?>">
                <label for="<?= $name;?>"><?= $label;?></label>
                <?php if  (isset($required) && $required ) {?>
                    <span class="form__field-sup">*</span>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="form__field form__field_<?= $name;?> ">
            <textarea id="<?= $name;?>" name="<?= $name;?>" rows="<?= (isset($rows) ? $rows : "7") ?>" class="form__control form__control_textarea form__control_<?= $name;?> <?= ($class ?? "") ?> " placeholder="<?= (isset($placeholder) ? $placeholder : "") ?>"  <?= (isset($required) && $required  ? "required" : "");?>><?= isset($value) ? $value : "";?></textarea>
        </div>
    </div>
<?php } ?>
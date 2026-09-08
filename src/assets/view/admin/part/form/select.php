<?php
/**
 * @var string $name
 * @var string $label
 * @var string $value
 * @var bool $required
 * @var array $options
 */
?>
<div class="mvc-box-field">
    <div class="mvc-field-label">
        <label for="<?= $name;?>"><?= $label;?></label>
        <?php if (isset($required) && $required ) {?>
        <span class="mvc-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="mvc-field b-field__select ">
        <select class="mvc-form-control" name="<?= $name;?>">
            <?php foreach ($options as $key => $title) {?>
                <option value="<?= $key;?>" <?= $key == $value ? "selected" : ""?> ><?= $title;?></option>
            <?php } ?>
        </select>
    </div>
</div>
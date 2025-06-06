<?php
/**
 * @var string $name
 * @var string $label
 * @var string $value
 * @var bool $required
 * @var array $options
 */
?>
<div class="b-box-field">
    <div class="b-field-label">
        <label for="<?= $name;?>"><?= $label;?></label>
        <?php if (isset($required) && $required ) {?>
        <span class="b-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="b-field b-field__select ">
        <select class="b-form-control" name="<?= $name;?>">
            <?php foreach ($options as $key => $title) {?>
                <option value="<?= $key;?>" <?= $key == $value ? "selected" : ""?> ><?= $title;?></option>
            <?php } ?>
        </select>
    </div>
</div>
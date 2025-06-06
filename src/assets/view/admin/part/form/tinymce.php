<div class="b-box-field b-box-field-<?= $name;?>">
    <div class="b-field-label  b-field-label-{{name}}">
        <label for="<?= $name;?>"><?= $label;?></label>
        <?php if  (isset($required) && $required ) {?>
            <span class="b-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="b-field b-field__textarea b-field__align-left ">
        <?php
        wp_editor(
            isset($value) ? $value : "",
            $name,
            array(
                'textarea_name' => $name,
                'media_buttons' => true,
                'textarea_rows' => 10,
            )
        );?>
    </div>
</div>
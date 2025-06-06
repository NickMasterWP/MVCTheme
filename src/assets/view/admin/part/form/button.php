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
    <div class="b-box-field b-box-field-{{name}}">
        <div class="b-field-label  b-field-label-{{name}}">
            <label for="<?= $args["name"];?>"><?= $args["label"];?>&nbsp;</label>
            <?php if (isset($args["required"]) && $args["required"] ) {?>
                <span class="b-sup-field">*</span>
            <?php } ?>
        </div>
        <div class="b-field b-field__input ">
            <button type="<?= $type ?? "button" ?>" id="button-<?= $name;?>" name="<?= $name;?>" value="<?= $value ?? ""?>"
                    class="button b-form-control b-form-control_button b-form-control_<?= $type;?> b-form-control_<?= $name;?> <?= ($class ?? "") ?>" <?= (isset($disabled) && $disabled  ? "disabled" : "");?>><?= $value;?></button>
        </div>
    </div>
<?php } ?>

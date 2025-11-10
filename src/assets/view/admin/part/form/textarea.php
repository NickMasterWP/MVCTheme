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

    <div class="b-box-field b-box-field-<?= $args["name"];?>">
        <div class="b-field-label  b-field-label-<?= $args["name"];?>">
            <label for="<?= $args["name"];?>"><?= $args["label"];?></label>
            <?php if (isset($args["required"]) && $args["required"] ) {?>
                <span class="b-sup-field">*</span>
            <?php } ?>
        </div>
        <div class="b-field b-field__input ">
            <textarea class="b-form-control b-form-control-textarea"
                      name="<?= $args["name"];?>"
                      value="<?= isset($args["required"]) ? $args["value"] : "";?>"
                      rows="<?= (isset($rows) ? $rows : "7") ?>"
                      <?= (isset($required) && $required  ? "required" : "");?>
                      placeholder="<?= isset($args["placeholder"]) ? $args["placeholder"] : "";?>" ><?= isset($value) ? $value : "";?></textarea>
        </div>
    </div>
<?php } ?>
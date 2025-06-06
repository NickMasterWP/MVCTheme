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
 * @var array $options
 * @var array $values
 */
?>
<?php if (  isset($name) ) { ?>
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
            <select id="<?= $name;?>" name="<?= $name;?>"  class="form__control form__control_select form__control_<?= $name;?> <?= (isset($isSelect2) ? "js-select" : "") ?>  <?= (isset($class) ? $class : "") ?>" <?= isset($multiple) && $multiple ? "multiple" : "" ;?> <?= isset($tags) && $tags ? " data-tags='yes' " : "" ;?> <?= (isset($required) && $required ) ? "required" : ""?> <?= (isset($count) && $count ) ? " data-count='".$count."' " : ""?> >
                <?php if (isset($empty) && $empty ) {?>
                    <option class="select-placeholder" disabled hidden  <?= is_array($values) && count($values) == 0 ?  "selected" : ""?> value=""><?= (isset($placeholder) ? $placeholder : "...") ?></option>
                <?php } ?>
                <?php foreach($options as $key => $name) {
                    ?>
                    <option value="<?= $key;?>" <?= is_array($values) && in_array((string)$key, $values) ? "selected" : ""?> ><?= $name;?></option>
                <?php }?>
            </select>
        </div>
    </div>
<?php } ?>

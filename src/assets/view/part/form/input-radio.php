<div class="box-field box-field-<?= $name;?>">
    <?php if ( isset($label) ) {?>
		<div class="field-label  field-label-<?= $name;?>">
			<label for="<?= $name;?>"><?= $label;?></label>
            <?php if  (isset($required) && $required ) {?>
			<span class="sup-field">*</span>
			<?php } ?>
		</div>
    <?php } ?>

		<div class="field field-column-<?= isset($column) ? $column : "2" ;?> field__radio ">
            <?php foreach ($options as $optionValue => $label) {?>
                <div class="field-radio-col">
                    <input type="radio" id="<?= $name . $optionValue;?>" name="<?= $name;?>" value="<?= $optionValue;?>" class="form-control-radio form-control-<?= $name;?> <?= (isset($class) ? $class : "") ?>" <?= $optionValue == $value ? "checked" : "" ?>  >
                    <label for="<?= $name . $optionValue;?>" class="field-radio-label <?= isset($classLabel) ? $classLabel : "" ?>">
                        <?= $label;?>
                    </label>
                </div>
            <?php } ?>
		</div>

</div>
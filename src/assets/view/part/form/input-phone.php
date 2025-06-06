<div class="box-field box-field-<?= $name;?>">
		<div class="field-label  field-label-<?= $name;?>">
			<label for="<?= $name;?>"><?= $label;?></label>
            <?php if  (isset($required) && $required ) {?>
			<span class="sup-field">*</span>
			<?php } ?>
		</div>
		<div class="field field__input ">
            <input type="text" id="<?= $name;?>" name="<?= $name;?>" value="<?= isset($value) ? $value : "";?>" class="form-control form-control-text form-control-<?= $name;?> <?= (isset($class) ? $class : "") ?>" placeholder="<?= (isset($placeholder) ? $placeholder : "") ?>">
		</div>
</div>
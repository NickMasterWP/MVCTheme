<div class="box-field box-field-<?= $name;?>">
		<div class="field-label  field-label-<?= $name;?>">
			<label for="<?= $name;?>"><?= $label;?></label>
            <?php if  (isset($required) && $required ) {?>
			<span class="sup-field">*</span>
			<?php } ?>
		</div>
		<div class="field field__textarea ">
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
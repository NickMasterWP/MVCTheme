
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
            <div class="form__star-rating">
                <input class="form__star-radio" type="radio" name="<?= $name;?>" value="5" id="form_star_<?= $name;?>_5">
                <label for="form_star_<?= $name;?>_5" class="form__star"></label>

                <input  class="form__star-radio" type="radio" name="<?= $name;?>" value="4" id="form_star_<?= $name;?>_4">
                <label for="form_star_<?= $name;?>_4" class="form__star"></label>

                <input class="form__star-radio" type="radio" name="<?= $name;?>" value="3" id="form_star_<?= $name;?>_3">
                <label for="form_star_<?= $name;?>_3" class="form__star"></label>

                <input class="form__star-radio" type="radio" name="<?= $name;?>" value="2" id="form_star_<?= $name;?>_2">
                <label for="form_star_<?= $name;?>_2" class="form__star"></label>

                <input class="form__star-radio" type="radio" name="<?= $name;?>" value="1" id="form_star_<?= $name;?>_1">
                <label for="form_star_<?= $name;?>_1" class="form__star"></label>
            </div>
        </div>
    </div>
<?php } ?>
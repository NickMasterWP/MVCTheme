<div class="mvc-box-field b-box-field-<?= $args["name"];?>">
    <div class="mvc-field-label  b-field-label-<?= $args["name"];?>">
        <label for="<?= $args["name"];?>"><?= $args["label"];?></label>
        <?php if (isset($args["required"]) && $args["required"] ) {?>
        <span class="mvc-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="mvc-field b-field__input ">
       <input class="mvc-form-control" type="file" name="<?= $args["name"];?>" value="<?= isset($args["required"]) ? $args["value"] : "";?>" >
    </div>
</div>
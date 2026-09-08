<div class="b-box-field b-box-field-<?= $args["name"];?>">
    <div class="b-field-label  b-field-label-<?= $args["name"];?>">
        <label for="<?= $args["name"];?>"><?= $args["label"];?></label>
        <?php if (isset($args["required"]) && $args["required"] ) {?>
        <span class="b-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="b-field b-field__input ">
        <textarea class="b-form-control" type="text" name="<?= $args["name"];?>" rows="4" placeholder="<?= isset($args["placeholder"]) ? $args["placeholder"] : "";?>" ><?= isset($args["required"]) ? $args["value"] : "";?></textarea>
    </div>
</div>
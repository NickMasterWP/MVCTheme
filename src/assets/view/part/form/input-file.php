<?php
/**
 * @var string $type
 * @var string $name
 * @var string $label
 * @var bool $required
 * @var string $value
 * @var string $image
 * @var string $class
 * @var string $placeholder
 * @var string $disabled
 * @var MVCTheme $MVCTheme
 */

    global $MVCTheme;
?>
<?php if (  isset($name) ) { ?>
    <div class="form__field-row form__field-row_<?= $name;?>"  >
        <?php if ( isset($label) && $label ) { ?>
            <div class="form__field-label  field__field-label_<?= $name;?>">
                <label for="<?= $name;?>"><?= $label;?></label>
                <?php if  (isset($required) && $required ) {?>
                    <span class="form__field-sup">*</span>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="form__file-row  ">
            <a class="form__control_file-preview js-fancybox " data-fancybox href="<?= $image ?? $MVCTheme->getThemeChildFileURL('assets/images/default.png');?>">
                <img class="js-file-preview" src="<?= $image ?? $MVCTheme->getThemeChildFileURL('assets/images/default.png');?>" alt="Превью изображения"/>
            </a>
            <label class="form__control form__control_file field field-file  <?= (isset($class) ? $class : "") ?>  ">
                <span class="form__control_file-placeholder js-file-text"><?= isset($placeholder) ?  $placeholder : ""?></span>
                <span class="form__control_file-btn"><?= isset($btn) ?  $btn : "Загрузить файл"?></span>
                <input class="js-load-image   " type="file" id="<?= str_replace(["[","]"], "_", $name);?>"
                       name="<?= $name;?>" data-min-width="<?= isset($minWidth) ? $minWidth : ""?>" data-min-height="<?= isset($minHeight) ? $minHeight : ""?>"
                       placeholder="<?= (isset($placeholder) ? $placeholder : "") ?>"   <?= (isset($required) && $required && $value == "" ? "required" : "");?>
                       accept="<?= (isset($accept) ? $accept : "") ?>"
                >
            </label>
        </div>

        <?php if ( is_numeric($value) ) { ?>
            <?php $mediaFile = Utils::getMediaFile($value);?>
            <?php if ($mediaFile) { ?>
                <a href="<?= $mediaFile;?>" target="_blank" class="form__control_file-link"><?= isset($fileName) ?  $fileName :  $label;?></a>
            <?php } ?>
        <?php } ?>
</div>
<?php } ?>
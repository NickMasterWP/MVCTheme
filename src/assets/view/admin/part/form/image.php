<?php
/**
 * @var array $args
 * */

$nameFormat = str_replace(["[","]"],"", $args["name"]);
$valueFile = "";
$valueHREF = "";

if ($args["value"]) {
    $filePath = get_attached_file($args["value"]);
    $valueFile = basename($filePath);
    $valueHREF =  wp_get_attachment_url($args["value"]);
}
?>
<div class="b-box-field">
    <div class="b-field-label">
        <label for="<?= $args["name"];?>"><?= $args["label"];?></label>
        <?php if (isset($args["required"]) && $args["required"] ) {?>
            <span class="b-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="b-field b-field__image ">
        <?php
            $image_url = $args["value"] ? wp_get_attachment_url($args["value"]) : '';
        ?>
        <div class="b-field-image <?= $args["value"] == "" ? "a-field-image-hide" : "";?>  js-mvc-image-<?= $nameFormat;?>">
            <input type="hidden" name="<?= $args["name"];?>" value="<?= $args["value"];?>" id="custom_image_<?= $nameFormat;?>">
            <a href="#" class="custom_remove_image_button button js-mvc-remove-button-<?= $nameFormat;?>">Remove Image</a>
        </div>
        <div class="b-field-actions">
            <a href="#" class="custom_upload_image_button button js-mvc-upload-button-<?= $nameFormat;?>">Upload Image</a>
        </div>
    </div>
    <div class="b-field-image-container">
        <a id="custom_image_preview_<?= $nameFormat;?>" target="_blank" href="<?= $valueHREF;?>"><?= $valueFile;?></a>
    </div>
</div>
<script>
    jQuery(document).ready(function($){
        var frame;
        $('.js-mvc-upload-button-<?= $nameFormat;?>').on('click', function(e){
            e.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#custom_image_<?= $nameFormat;?>').val(attachment.id);
                $('#custom_image_preview_<?= $nameFormat;?>').html(attachment.filename);
                $('#custom_image_preview_<?= $nameFormat;?>').attr("href", attachment.url);
                $(".js-mvc-image-<?= $nameFormat;?>").removeClass("a-field-image-hide");
            });

            frame.open();
        });

        $('.js-mvc-remove-button-<?= $nameFormat;?>').on('click', function(e){
            e.preventDefault();
            $('#custom_image_<?= $nameFormat;?>').val();
            $('#custom_image_preview_<?= $nameFormat;?>').attr('src', "");
            $(".js-mvc-image-<?= $nameFormat;?>").addClass("a-field-image-hide");
        });
    });
</script>
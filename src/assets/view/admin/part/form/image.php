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
        <div class="b-field-image <?= $args["value"] == "" ? "a-field-image-hide" : "";?>  js-mvc-image-<?= $args["name"];?>">
            <div class="b-field-image-container"><img src="<?= $image_url;?>" id="custom_image_preview_<?= $args["name"];?>"></div>
            <input type="hidden" name="<?= $args["name"];?>" value="<?= $args["value"];?>" id="custom_image_<?= $args["name"];?>">
            <a href="#" class="custom_remove_image_button button js-mvc-remove-button-<?= $args["name"];?>">Remove Image</a>
        </div>
        <div class="b-field-actions">
            <a href="#" class="custom_upload_image_button button js-mvc-upload-button-<?= $args["name"];?>">Upload Image</a>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($){
        var frame;
        $('.js-mvc-upload-button-<?= $args["name"];?>').on('click', function(e){
            e.preventDefault();

            // Если медиабиблиотека уже открыта, используем ее.
            if (frame) {
                frame.open();
                return;
            }

            // Создаем медиабиблиотеку на базе wp.media
            frame = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#custom_image_<?= $args["name"];?>').val(attachment.id);
                $('#custom_image_preview_<?= $args["name"];?>').attr('src', attachment.url);
                $(".js-mvc-image-<?= $args["name"];?>").removeClass("a-field-image-hide");
            });

            frame.open();
        });

        $('.js-mvc-remove-button-<?= $args["name"];?>').on('click', function(e){
            e.preventDefault();
            $('#custom_image_<?= $args["name"];?>').val();
            $('#custom_image_preview_<?= $args["name"];?>').attr('src', "");
            $(".js-mvc-image-<?= $args["name"];?>").addClass("a-field-image-hide");
        });
    });
</script>
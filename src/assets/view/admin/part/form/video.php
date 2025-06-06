<div class="b-box-field">
    <div class="b-field-label">
        <label for="<?= $args["name"];?>"><?= $args["label"];?></label>
        <?php if (isset($args["required"]) && $args["required"] ) {?>
            <span class="b-sup-field">*</span>
        <?php } ?>
    </div>
    <div class="b-field b-field__video ">
        <?php
            $video_url = $args["value"] ? wp_get_attachment_url($args["value"]) : '';
        ?>
        <div class="b-field-video <?= $args["value"] == "" ? "a-field-video-hide" : "";?>  js-mvc-video-<?= $args["name"];?>">
            <div class="b-field-video-container">
                <video width="100%" controls>
                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4" id="custom_video_preview_<?= $args["name"];?>">
                </video>
            </div>
            <input type="hidden" name="<?= $args["name"];?>" value="<?= $args["value"];?>" id="custom_video_<?= $args["name"];?>">
            <a href="#" class="custom_remove_video_button button js-mvc-remove-button-<?= $args["name"];?>">Remove Video</a>
        </div>
        <div class="b-field-actions">
            <a href="#" class="custom_upload_video_button button js-mvc-upload-button-<?= $args["name"];?>">Upload Video</a>
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
                    text: 'Use this video'
                },
                multiple: false
            });

            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#custom_video_<?= $args["name"];?>').val(attachment.id);
                $('#custom_video_preview_<?= $args["name"];?>').attr('src', attachment.url);
                $(".js-mvc-video-<?= $args["name"];?>").removeClass("a-field-video-hide");
            });

            frame.open();
        });

        $('.js-mvc-remove-button-<?= $args["name"];?>').on('click', function(e){
            e.preventDefault();
            $('#custom_video_<?= $args["name"];?>').val();
            $('#custom_video_preview_<?= $args["name"];?>').attr('src', "");
            $(".js-mvc-video-<?= $args["name"];?>").addClass("a-field-video-hide");
        });
    });
</script>
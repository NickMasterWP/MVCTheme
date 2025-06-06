<div class="cf-header">
    <?php $form_size = ( !empty($args["form-size"]) ? $args["form-size"] : "max");?>
    <h1><?= $args["title"];?><?= ( !empty($args["tab"]) ? " - ".$args["tab"] : "");?></h1>
    <div class="cf-header-btns">

        <?php if ( !empty($args["action"])  && !empty($args["btn"]) ){?>
            <a class="cf-btn cf-btn-big button button-primary js-cf-ajax-html js-cf-ajax-html   " href="#cf-popup-<?= $form_size;?>" data-modal=false data-action="<?= $args["action"];?>" data-html="cf-popup-<?= $form_size;?>" data-param=""><?= $args["btn"];?></a>
        <?php } else if ( !empty($args["btn_link"])  && !empty($args["btn"]) ) {?>
            <a class="cf-btn cf-btn-big button button-primary " href="<?= $args["btn_link"];?>"  ><?= $args["btn"];?></a>
        <?php }?>
        <?php if ( !empty($args["btns"])  && is_array($args["btns"]) ){
            foreach ( $args["btns"] as $btn) {
                $form_size = ( ! empty( $btn["form-size"] ) ? $btn["form-size"] : "max" );
                $id = ( ! empty( $btn["id"] ) ? $btn["id"] : "" );
                $param = ( ! empty( $btn["param"] ) ? $btn["param"] : "" );
                if ( !empty($btn["action"])  && !empty($btn["name"]) ) { ?>
                    <a class="cf-btn cf-btn-big button button-primary js-cf-ajax-html js-cf-ajax-html" id="<?= $id;?>" href="#cf-popup-<?= $form_size;?>" data-modal=false data-action="<?= $btn["action"];?>" data-html="cf-popup-<?= $form_size;?>" data-param="<?= $param;?>"><?= $btn["name"];?></a>
                <?php } else if ( !empty($btn["btn_link"])  && !empty($btn["name"]) ) {?>
                    <a class="cf-btn cf-btn-big button button-primary " id="<?= $id;?>" href="<?= $btn["btn_link"];?>"  ><?= $btn["name"];?></a>
                <?php }
            }
        } ?>

    </div>

</div>

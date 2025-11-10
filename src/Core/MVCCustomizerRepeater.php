<?php

namespace MVCTheme\Core;

use MVCTheme\MVCTheme;
use WP_Customize_Control;

if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return null;
}

class MVCCustomizerRepeater extends WP_Customize_Control {

    public $id;
    private $boxtitle = array();
    private $add_field_label = array();
    private $customizer_icon_container = '';
    private $allowed_html = array();

    private $fields = array();

    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );

        $options = $args["options"];

        $this->add_field_label = "Добавить";

        if ( ! empty( $options['btn_label'] ) ) {
            $this->add_field_label = $options['btn_label'];
        }

        $this->boxtitle = $this->label;

        if ( ! empty( $options['fields'] ) ) {
            $this->fields = $options['fields'];
        }

        if ( ! empty( $id ) ) {
            $this->id = $id;
        }

        $this->customizer_icon_container =  'admin/customizer-repeater/icons';

        $allowed_array1 = wp_kses_allowed_html( 'post' );
        $allowed_array2 = array(
            'input' => array(
                'type'        => array(),
                'class'       => array(),
                'placeholder' => array()
            )
        );

        $this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
    }

    /*Enqueue resources for the control*/
    public function enqueue() {
        $MVCTheme = MVCTheme::getInstance();
        wp_enqueue_style( 'font-awesome', $MVCTheme->getMVCThemeFileURL('/assets/css/admin/customizer-repeater/font-awesome.min.css'), array(), $MVCTheme->getVersion() );

        wp_enqueue_style( 'customizer-repeater-admin-stylesheet', $MVCTheme->getMVCThemeFileURL('/assets/css/admin/customizer-repeater/admin-style.css'), array(), $MVCTheme->getVersion() );

        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script( 'customizer-repeater-script', $MVCTheme->getMVCThemeFileURL('/assets/js/admin/customizer-repeater/customizer_repeater.js'), array('jquery', 'jquery-ui-draggable', 'wp-color-picker' ), $MVCTheme->getVersion(), true  );

        wp_enqueue_script( 'customizer-repeater-fontawesome-iconpicker', $MVCTheme->getMVCThemeFileURL('/assets/js/admin/customizer-repeater/fontawesome-iconpicker.min.js'), array( 'jquery' ), $MVCTheme->getVersion(), true );

        wp_enqueue_style( 'customizer-repeater-fontawesome-iconpicker-script', $MVCTheme->getMVCThemeFileURL('/assets/css/admin/customizer-repeater/fontawesome-iconpicker.min.css'), array(), $MVCTheme->getVersion() );
    }

    public function render_content() {

        $this_default = json_decode( $this->setting->default );

        $values = $this->value();

        $json = json_decode( $values );

        if ( ! is_array( $json ) ) {
            $json = array( $values );
        }

        ?>

        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
            <?php  $this->iterate_array( $json ); ?>
            <input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?> class="customizer-repeater-colector" value="
		<?php echo esc_textarea( $this->value() ); ?>"/>
        </div>
        <button type="button" class="button add_field customizer-repeater-new-field"><?= esc_html( $this->add_field_label ); ?></button>
        <?php
    }

    private function iterate_array($values = array()){
        /*Counter that helps checking if the box is first and should have the delete button disabled*/
        $it = 0;

        //var_dump($values);
        foreach($values as $index => $value) {

            $value_name = ( empty( $value->{$this->fields[0][0]} ) ? "" : $value->{$this->fields[0][0]} );

            $title = ( !empty($value_name) ?  $value_name : $this->boxtitle);
            ?>

            <div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
                <div class="customizer-repeater-customize-control-title">
                    <?= $title ?>
                </div>
                <div class="customizer-repeater-box-content-hidden repeater-box-fileds">
                    <?php foreach($this->fields as $field) {

                        $options = [];
                        $options["name"] = $field[0];
                        $options["title"] = $field[1];
                        $options["default"] = $field[2];
                        $options["type"] = ( empty($field[3]) ? "text" : $field[3]);

                        if ( isset($field[4]) ) {
                            $options["choice"] = $field[4];
                        } else {
                            $options["choice"] = [];
                        }

                        $value_field = ( empty($value->{$options["name"]} ) ? $options["default"] : $value->{$options["name"]} );

                        if ( $options["type"] == "text"  ) {
                            $this->input_control($options, $value_field);
                        } else if ( $options["type"] == "textarea"  ) {
                            $this->textarea_control($options, $value_field);
                        } else if ( $options["type"] == "repeat"  ) {
                            $this->repeater_control($options, $value_field);
                        }

                    }

                    if ( $index != 0 ) { ?>
                        <button type="button" class="social-repeater-general-control-remove-field">Удалить</button>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }

    private function input_control( $options, $value='' ) { ?>
        <span class="customize-control-title"><?= $options['title']  ; ?></span>
        <input type="text" value="<?= esc_attr($value)  ; ?>" class="customizer-repeater-text-control customizer-repeater-value" name="<?= $options['name']  ; ?>" placeholder="<?= esc_attr( $options['title'] ); ?>" />

        <?php
    }

    private function textarea_control( $options, $value='' ){ ?>
        <span class="customize-control-title"><?= $options['title']  ; ?></span>
        <textarea class="customizer-repeater-textarea-control customizer-repeater-value " name="<?= $options['name']  ; ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"><?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?></textarea>
    <?php }


    private function color_control( $options, $value='' ){ ?>
        <span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
        <div class=" ">
            <input type="text" class="customizer-repeater-color-control customizer-repeater-value" name="<?= $options['name']  ; ?>" value="<?= esc_attr($value)  ; ?>" />
        </div>
    <?php }

    private function image_control($options = '', $value = ''){ ?>
        <div class="customizer-repeater-image-control">
					<span class="customize-control-title">
                <?= $options['title']  ; ?>
            </span>
            <input type="text" class="widefat custom-media-url" name="<?= $options['name']  ; ?>" value="<?= esc_attr( $value ); ?>">
            <input type="button" class="button button-secondary customizer-repeater-custom-media-button" value="<?php esc_attr_e( 'Upload Image','your-textdomain' ); ?>" />
        </div>
        <?php
    }


    private function repeater_control($options, $json_value='') {
        $social_repeater = array();
        $show_del        = 0; ?>
        <span class="customize-control-title customize-control-repeater-title"><?= $options['title']  ; ?></span>
        <div class="customizer-repeater-internal" data-name="<?= $options["name"]; ?>">
            <?php
            if(!empty($json_value)) {
                $values = json_decode( html_entity_decode( $json_value ), true );
            } else {
                $values = [  [] ];
            }


            $fields = $options["choice"]["fields"] ;

            foreach($values as $index => $value) { ?>

                <div class="customizer-repeater-social-repeater-container">

                    <?php 		//var_dump($fields);
                    foreach($fields as $field) {
                        $options_field = [];
                        $options_field["name"] = $field[0];
                        $options_field["title"] = $field[1];
                        $options_field["default"] = $field[2];
                        $options_field["type"] = ( empty($field[3]) ? "text" : $field[3]);

                        $value_field = (  is_array($value) && isset($value[$options_field["name"]] ) ? $value[$options_field["name"]] : $options_field["default"]  );

                        if ( $options_field["type"] == "text"  ) {
                            $this->input_control($options_field, $value_field);
                        } else if ( $options_field["type"] == "textarea"  ) {
                            $this->textarea_control($options_field, $value_field);
                        } else if ( $options_field["type"] == "repeat"  ) {
                            $this->repeater_control($options_field, $value_field);
                        }
                    }

                    if ( $index != 0 ) { ?>
                        <button type="button" class="social-repeater-remove-social-item">Удалить</button>
                    <?php } ?>

                </div>

            <?php } ?>
        </div>


        <button type="button" class="button add_field   social-repeater-add-social-item"><?= $options["choice"]["btn_label"] ; ?></button>
        <?php
    }

}
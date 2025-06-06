<?php

namespace MVCTheme\Traits;

trait MVCShortcodeTrait {

    public function add_shortcode($name) {
        add_shortcode($name, [$this, 'shortcode_exec']);
    }

    public function shortcode_exec($atts, $content = '', $tag = '') {
        $args = ['content' => $content];
        if (is_array($atts)) {
            $args = array_merge($args, $atts);
        }

        return View::partial("shortcodes/" . $tag, $args);
    }

}
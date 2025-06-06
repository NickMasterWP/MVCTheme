<?php

namespace MVCTheme\Traits;

trait MVCMetaBoxesCommentsTrait {

    private $metaBoxesComments;

    function initializeMetaboxComments() {
        add_action('add_meta_boxes_comment', array($this, 'addCommentMetaBox'));
        add_action('edit_comment', array($this, 'saveMetaBoxesCommentAction'));
    }

    function addCommentMetaBox($name, $title) {
        $this->metaBoxesComments[$name] = ["title" => $title, "fields" => []];
    }

    function addCommentMetaBoxField($meta_box_name, $name, $title, $type = "text", $options = null) {
        $this->metaBoxesComments[$meta_box_name]["fields"][] = ["name" => $name, "title" => $title, "type" => $type, "options" => $options];
    }

    function addCommentMetaBoxesAction($comment) {
        foreach ($this->metaBoxesComments as $name => $metaBox) {
            add_meta_box(
                $name,
                $metaBox["title"],
                array($this, "metaBoxRender"),
                'comment',
                'normal',
                'high',
                ['comment' => $comment]
            );
        }
    }

    function metaBoxCommentRender($comment, $meta) {
        $fields = $this->metaBoxesComments[$meta["id"]]["fields"];
        wp_nonce_field("COMMENT_META", 'comment_meta_nonce');
        echo '<table class="form-table">';
        foreach ($fields as $field) {
            $value = get_comment_meta($comment->comment_ID, $field["name"], true);
            echo $this->printField($field, $value);
        }
        echo '</table>';
    }

    function saveMetaBoxesCommentAction($comment_id) {
        if (!isset($_POST['comment_meta_nonce']) || !wp_verify_nonce($_POST['comment_meta_nonce'], 'COMMENT_META')) {
            return;
        }

        if (!current_user_can('edit_comment', $comment_id)) {
            return;
        }

        foreach ($this->metaBoxesComments as $metaBox) {
            foreach ($metaBox["fields"] as $field) {
                $name = $field["name"];
                if (isset($_POST[$name])) {
                    $value = sanitize_text_field($_POST[$name]);
                    update_comment_meta($comment_id, $name, $value);
                }
            }
        }
    }
}

<?php

namespace MVCTheme\Traits;

use MVCTheme\Core\MVCView;

trait MVCMetaBoxesTrait {

    private $metaBoxes = [];

    public function addMetaBox($name, $title, $post_types) {
        $this->metaBoxes[$name] = [
            "title" => $title,
            "post_types" => $post_types,
            "fields" => []
        ];
    }

    public function addMetaBoxField($meta_box_name, $name, $title, $type = "text", $options = null) {
        if (!isset($this->metaBoxes[$meta_box_name])) {
            return;
        }
        $this->metaBoxes[$meta_box_name]["fields"][] = [
            "name" => $name,
            "title" => $title,
            "type" => $type,
            "options" => $options
        ];
    }

    public function addMetaBoxesAction() {
        foreach ($this->metaBoxes as $name => $metaBox) {
            add_meta_box($name, $metaBox["title"], [$this, "metaBoxRender"], $metaBox["post_types"]);
        }
    }

    public function getFieldsMetaBoxByPostType($postType) : array {
        $fields = [];
        foreach ($this->metaBoxes as $name => $metaBox) {
            if (in_array($postType, $metaBox["post_types"])) {
                $fields = $metaBox["fields"];
                break;
            }
        }
        return $fields;
    }

    public static function printField($field, $value) {
        switch ($field["type"]) {
            case "text":
                return MVCView::adminPart("form/input-text", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "textarea":
                return MVCView::adminPart("form/textarea", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "number":
                return MVCView::adminPart("form/input-number", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "date":
                return MVCView::adminPart("form/input-date", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "select":
                return MVCView::adminPart("form/select", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "options" => $field["options"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "image":
                return MVCView::adminPart("form/image", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "options" => $field["options"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "video":
                return MVCView::adminPart("form/video", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "options" => $field["options"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "tinymce":
                return MVCView::adminPart("form/tinymce", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "required" => $field["required"] ?? false,
                    "value" => $value
                ]);
            case "repeater":
                return MVCView::adminPart("form/repeater", [
                    "name" => $field["name"],
                    "label" => $field["title"],
                    "fields" => $field["options"],
                    "value" => $value
                ]);
            default:
                return '';
        }
    }

    public function metaBoxRender($post, $meta) {
        $fields = $this->getFieldsMetaBoxByPostType($post->post_type);
        if (!$fields) {
            return;
        }

        wp_nonce_field("MVC_THEME", 'mvctheme');

        echo MVCView::adminPart("form/start-fields", ["class" => "b-meta-box"]);

        foreach ($fields as $field) {
            $value = get_post_meta($post->ID, $field["name"], true);
            echo $this->printField($field, $value);
        }

        echo MVCView::adminPart("form/end-fields");
    }

    public function saveMetaBoxesAction($postId) {

        if (count($_POST) === 0) {
            return;
        }

        if (!isset($_POST['mvctheme']) || !wp_verify_nonce($_POST['mvctheme'], "MVC_THEME")) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        $post = get_post($postId);
        $fields = $this->getFieldsMetaBoxByPostType($post->post_type);

        foreach ($fields as $field) {
            $name = $field["name"];
            if (isset($_POST[$name])) {
                if ($field["type"] === "repeater") {
                    $value = [];

                    foreach ($_POST[$name] as $index => $item) {
                        if ($index === "__index__") {
                            continue;
                        }
                        $row = [];
                        foreach ($field["options"] as $subField) {
                            $row[$subField["name"]] = sanitize_text_field($item[$subField["name"]] ?? '');
                        }
                        $value[] = $row;
                    }
                } elseif ($field["type"] !== "tinymce") {
                    $value = sanitize_text_field($_POST[$name]);
                } else {
                    $value = $_POST[$name];
                }
                update_post_meta($postId, $name, $value);
                do_action("mvc_save_meta_box_field", $postId, $name, $value);
            }
        }
    }

}
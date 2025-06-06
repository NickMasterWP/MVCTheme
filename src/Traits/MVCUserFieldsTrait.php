<?php

namespace MVCTheme\Traits;

trait MVCUserFieldsTrait {

    private $userFields = [];
    private $adminPanelUser = true;

    public function addUserFields($name, $title, $type = "text", $options = null) {
        $this->userFields[] = [
            "name" => $name,
            "title" => $title,
            "type" => $type,
            "options" => $options
        ];
    }

    public function showUserField($user) {
        $fields = $this->userFields;
        $content = View::adminPart("form/start-fields", ["class" => "b-meta-box"]);
        foreach ($fields as $field) {
            $value = get_the_author_meta($field["name"], $user->ID);
            $content .= $this->printField($field, $value);
        }
        $content .= View::adminPart("form/end-fields");
        echo View::adminPart("form/user-edit-form", [
            "title" => __("Специальные настройки пользователя", "mvctheme"),
            "content" => $content
        ]);
    }

    public function getUserFields() {
        return $this->userFields;
    }

    public function saveUserField($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return;
        }
        $fields = $this->userFields;
        foreach ($fields as $field) {
            $name = $field["name"];
            if (isset($_POST[$name])) {
                if ($field["type"] !== "tinymce") {
                    $value = sanitize_text_field($_POST[$name]);
                } else {
                    $value = $_POST[$name];
                }
                update_user_meta($user_id, $name, $value);
            }
        }
    }


    public function setAdminPanelUser(bool $value): void {
        $this->adminPanelUser = $value;
    }

}
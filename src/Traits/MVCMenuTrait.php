<?php

namespace MVCTheme\Traits;

trait MVCMenuTrait {

    private $menu = [];
    private $menu_field = [];

    public function addMenu($name, $title): void {
        $this->menu[$name] = $title;
    }

    public function getMenus(): array {
        return $this->menu;
    }

    public function registerMenu(): void {
        register_nav_menus($this->menu);
    }

    public function addMenuField($name, $title, $type = "text"): void {
        $this->menu_field[$name] = ["title" => $title, "type" => $type];
    }

    public function wpNavMenuItemCustomFields($item_id, $item, $depth, $args, $id) {
        foreach ($this->menu_field as $field => $setting) {
            $value = get_post_meta($item_id, '_menu_' . $field, true);
            echo '<p class="description description-wide"><label>';
            if ($setting["type"] == "checkbox") {
                echo '<input type="checkbox" ' . checked('yes', $value, false) . ' name="menu-item-' . $field . '[' . $item_id . ']"> ' . $setting["title"];
            } else {
                echo $setting["title"] . '<br><input class="widefat" type="text" value="' . esc_attr($value) . '" name="menu-item-' . $field . '[' . $item_id . ']">';
            }
            echo '</label></p>';
        }
    }

    public function menuField() {
        return $this->menu_field;
    }

    public function wpUpdateNavMenuItem($menu_id, $menu_item_db_id) {
        foreach ($this->menu_field as $field => $setting) {
            if ($setting["type"] == "checkbox") {
                $meta_value = isset($_POST['menu-item-' . $field][$menu_item_db_id]) && 'on' == $_POST['menu-item-' . $field][$menu_item_db_id] ? 'yes' : 'no';
            } else {
                $meta_value = isset($_POST['menu-item-' . $field][$menu_item_db_id]) ? sanitize_text_field($_POST['menu-item-' . $field][$menu_item_db_id]) : '';
            }
            update_post_meta($menu_item_db_id, '_menu_' . $field, $meta_value);
        }
    }

}
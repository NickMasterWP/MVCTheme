<?php

namespace MVCTheme\Traits;

trait MVCMenuAdminPanelTrait {
    private $menuAdminPanel = [];


    public function initializeMenuAdminPanel() {
        add_action('admin_menu', array($this, 'registerMenuAdminPanel'));
    }

    public function addMenuAdminPanel($nameMenu, $slugMenu, $nameAdminPageController, $slugMenuParent = null,  $position = 10, $titlePage = null, $icon = 'dashicons-groups',  $capability = 'manage_options',  ) {
        $this->menuAdminPanel[] = [
            "name" => $nameMenu,
            "slug" => $slugMenu,
            "slugParent" => $slugMenuParent,
            "nameAdminPageController" => $nameAdminPageController,
            "position" => $position,
            "title" => $titlePage ?? $nameMenu,
            "icon" => $icon,
            "capability" => $capability
        ];
    }

    function registerMenuAdminPanel() {
        foreach ($this->menuAdminPanel as $menu) {

            $className = $menu["nameAdminPageController"];
            $file = $this->getThemeChildFilePath("includes/controller/admin-page/".$className.".php");

            if (file_exists($file)) {
                include_once $file;
                $controller = new $className($menu["slug"], $menu["name"]);
            } else {
                continue;
            }

            if ($menu["slugParent"]) {
                add_submenu_page(
                    $menu["slugParent"],
                    $menu["title"],
                    $menu["name"],
                    $menu["capability"],
                    $menu["slug"],
                    [$controller, "run"]
                );
            } else {
                add_menu_page(
                    $menu["title"],
                    $menu["name"],
                    $menu["capability"],
                    $menu["slug"],
                    [$controller, "run"],
                    $menu["icon"],
                    $menu["position"]
                );
            }
        }
    }
}
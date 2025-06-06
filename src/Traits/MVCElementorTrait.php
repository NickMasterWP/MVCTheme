<?php

namespace MVCTheme\Traits;

trait MVCElementorTrait {
    private $elementorWidgets = [];
    private $elementorCategories = [];

    function addElementorWidgetCategories( $elements_manager ) {

        $categories = [];
        $categories['mvc_theme'] =
            [
                'title' => $this->name,
                'icon' => 'fa fa-plug',
            ];

        foreach ($this->elementorCategories as $name => $title) {
            $categories[$name] =
                [
                    'title' => $title,
                    'icon' => 'fa fa-plug',
                ];
        }

        $old_categories = $elements_manager->get_categories();
        $categories = array_merge($categories, $old_categories);

        $set_categories = function ( $categories ) {
            $this->categories = $categories;
        };

        $set_categories->call( $elements_manager, $categories );
    }

    function addElementorBlock($name  ) {

        $this->elementorWidgets[] = $name;

    }

    function addElementorCategory($name, $title  ) {

        $this->elementorCategories[$name] = $title;

    }

    function registerElementorWidgets() : void {

        if (class_exists("\Elementor\Widget_Base")) {

            foreach ($this->elementorWidgets as $elementorName ) {
                $fileElementor = $this->getThemeChildFilePath("app/Controller/Elementor/".$elementorName.".php");

                if (file_exists($fileElementor)) {
                    include_once $fileElementor;
                }
            }

        }

    }
}

<?php

namespace MVCTheme\Traits;

trait MVCTaxonomyTrait {

    private $taxonomies = [];

    public function addTaxonomies($taxonomyName, $title, $postTypes, $hierarchical = false) {
        $this->taxonomies[$taxonomyName] = [
            "title" => $title,
            "post_types" => $postTypes,
            "hierarchical" => $hierarchical
        ];
    }

    public function registerTaxonomies() {
        foreach ($this->taxonomies as $taxonomyName => $options) {
            $this->registerTaxonomy($taxonomyName, $options["title"], $options["post_types"], $options["hierarchical"]);
        }
    }

    public function registerTaxonomy($name, $title, $posttypes, $hierarchical) {
        register_taxonomy($name, $posttypes, [
            'label' => $title,
            'labels' => [
                'name'              => $title,
                'singular_name'     => $title,
                'search_items'      => __('Поиск', 'mvctheme'),
                'all_items'         => __('Все', 'mvctheme'),
                'view_item'         => __('Посмотреть', 'mvctheme'),
                'parent_item'       => __('Родитель', 'mvctheme'),
                'parent_item_colon' => __('Родитель', 'mvctheme'),
                'edit_item'         => __('Редактировать', 'mvctheme'),
                'update_item'       => __('Обновить', 'mvctheme'),
                'add_new_item'      => __('Доавить новый', 'mvctheme'),
                'new_item_name'     => __('Новый', 'mvctheme'),
                'menu_name'         => $title,
                'back_to_items'     => __('← Назад', 'mvctheme'),
            ],
            'description'   => '',
            'public'        => true,
            'hierarchical'  => $hierarchical,
            'rewrite'       => true,
            'capabilities'  => [],
            'meta_box_cb'   => 'post_categories_meta_box',
            'show_in_rest'  => true,
            'rest_base'     => null,
        ]);
    }

}
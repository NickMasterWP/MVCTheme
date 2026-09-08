<?php

namespace MVCTheme\Traits;

trait MVCPostTypeTrait {

    private $postTypes = [];

    public function addPostType($postType, $name, $taxonomies = [], $supports = ['title', 'thumbnail', 'editor', 'author', 'excerpt', 'page-attributes'], $statuses = [], $public = true ) {
        $this->postTypes[$postType] = [
            "name" => $name,
            "taxonomies" => $taxonomies,
            "supports" => $supports,
            "statuses" => $statuses,
            "public" => $public
        ];
    }

    public function getPostTypes() : array {
        return $this->postTypes;
    }

    public function registerPostTypes() {
        foreach ($this->postTypes as $postType => $options) {
            $this->registerPostType($postType, $options["name"], $options["supports"], $options["public"]);

            if ($options["statuses"]) {
                foreach ($options["statuses"] as $name => $title) {
                    $this->registerPostStatus($name, $title);
                    $this->addPostStatusToType($postType, $name, $title);
                }
            }
        }
    }

    public function registerPostType($posttype, $name, $supports, $public) {
        register_post_type($posttype, array(
            'labels' => array(
                'name'               => __( $name, 'mvctheme' ),
                'singular_name'      => __( $name, 'mvctheme' ),
                'add_new'            => __( 'Добавить' , 'mvctheme' ),
                'add_new_item'       => __( 'Добавить' , 'mvctheme' ),
                'edit_item'          => __( 'Редактировать' , 'mvctheme' ),
                'new_item'           => __( 'Новый' , 'mvctheme' ),
                'view_item'          => __( 'Посмотреть', 'mvctheme' ),
                'search_items'       => __( 'Поиск', 'mvctheme' ),
                'not_found'          => __( 'Не найден', 'mvctheme' ),
                'not_found_in_trash' => __( 'Не найден', 'mvctheme' ),
            ),
            'public'            => $public,
            'publicly_queryable'=> true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            '_builtin'          => false,
            'capability_type'   => 'page',
            'show_in_rest'      => true,
            'supports'          => $supports,
            'rewrite'           => true,
            'query_var'         => true,
            'can_export'        => true
        ));
    }

    public function registerPostStatus($status, $title) {
        register_post_status($status, array(
            'label'                     => _x($title, 'mvctheme'),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'show_in_front'             => true,
            'label_count'               => _n_noop($status.' <span class="count">(%s)</span>', $status.' <span class="count">(%s)</span>'),
        ));
    }

    public function addPostStatusToType($postType, $status, $title) {
        add_action('post_submitbox_misc_actions', function() use ($postType, $status, $title) {
            global $post, $post_type;
            if ($post_type === $postType) {
                $complete = $post->post_status === $status ? ' selected="selected"' : '';
                echo "<script>
                    jQuery(document).ready(function($){
                        $('select#post_status').append('<option value=\"$status\"$complete>$title</option>');
                    });
                </script>";
            }
        });

        add_filter('display_post_states', function($states, $post) use ($postType, $status, $title) {
            if ($post->post_type === $postType && $post->post_status === $status) {
                $states[] = __($title, 'mvctheme');
            }
            return $states;
        }, 10, 2);
    }

}
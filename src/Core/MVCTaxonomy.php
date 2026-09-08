<?php
namespace MVCTheme\Core;

class MVCTaxonomy {

    static function getTags($taxonomyName) {

        $terms = get_terms(array(
            'taxonomy' => $taxonomyName,
            'hide_empty' => false,
        ));

        if (is_wp_error($terms)) {
            return [];
        }

        $result = [];
        foreach ($terms as $term) {
            $result[$term->term_id] = $term->name;
        }

        return $result;
    }


}
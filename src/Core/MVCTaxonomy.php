<?php
namespace MVCTheme\Core;

class MVCTaxonomy {

    static function getTags($taxonomyName) {

        $terms = get_terms(array(
            'taxonomy' => [$taxonomyName],
            'hide_empty' => true,
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

    static function getTagNameById($tagId, $taxonomyName): string
    {
        $term = get_term($tagId, $taxonomyName);
        if (!is_wp_error($term) && $term && !empty($term->name)) {
            return $term->name;
        }
        return '';
    }

}
<?php

namespace MVCTheme\Core;

class MVCTerm {

    public $id;
    public $name;
    public $term;

    /**
     * @throws Exception
     */
    static function create($name, $taxonomyName, $slug = '', $description = '') {
        $term_data = wp_insert_term(
            $name,
            $taxonomyName,
            [
                'slug' => $slug,
                'description' => $description
            ]
        );

        if (is_wp_error($term_data)) {
            throw new Exception($term_data->get_error_message());
        }

        return new static($term_data['term_id']);
    }

    /**
     * @throws Exception
     */
    public function __construct($data) {
        if (is_numeric($data)) {
            $this->term = get_term($data );
        } else if (is_object($data) && isset($data->term_id)) {
            $this->term = $data;
        } else {
            throw new Exception(__("MVCTerms: data wrong format", "mvctheme"));
        }
    }

    public function id() {
        return isset($this->term->term_id) ? $this->term->term_id : false;
    }

    public function name() {
        return isset($this->term->name) ? $this->term->name : '';
    }

    public function slug() {
        return isset($this->term->slug) ? $this->term->slug : '';
    }

    public function description() {
        return isset($this->term->description) ? $this->term->description : '';
    }

    public function count() {
        return isset($this->term->count) ? $this->term->count : 0;
    }

    public function link() {
        return get_term_link($this->term->term_id, static::taxonomyName);
    }

    public function saveName($name) {
        return $this->updateField('name', $name);
    }

    public function saveSlug($slug) {
        return $this->updateField('slug', $slug);
    }

    public function saveDescription($description) {
        return $this->updateField('description', $description);
    }


    public function delete() {
        $result = wp_delete_term($this->term->term_id );
        return !is_wp_error($result);
    }

    public function saveMeta($key, $value) {
        return update_term_meta($this->term->term_id, $key, $value);
    }

    public function getMeta($key) {
        return get_term_meta($this->term->term_id, $key, true);
    }
}
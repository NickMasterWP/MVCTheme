<?php

namespace MVCTheme\Core;

class MVCPosts {

    public $id;
    public $name;
    public $post;

    const postType = "post";

    /**
     * @throws Exception
     */
    static function create($status = 'publish')  {
        $post_data = array(
            'post_title'    => "",
            'post_type'    => static::postType,
            'post_content'  => '',
            'post_status'   => $status,
            'post_author'   => MVCUser::getCurrentUserID()
        );

        $post_id = wp_insert_post( $post_data );
        return new static($post_id);
    }

    /**
     * @throws Exception
     */
    static function getCurrentPost() {
        if (is_singular()) {
            return new static(get_the_ID());
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function __construct($data ) {

        if ( is_numeric($data) ) {
            $this->post = get_post($data);
        } else if ( is_object($data) ) {
            $this->post = $data;
        } else {
            throw new Exception( __("MVCPosts: data wrong format", "mvctheme") );
        }

    }

    public function id() {
        if ( isset($this->post) && isset($this->post->ID) ) {
            return $this->post->ID;
        }
        return false ;
    }

    public function getThumbnailUrl($size = "thumbnail") {
        return get_the_post_thumbnail_url( $this->post, $size );
    }

    public function authorId() {
        if ( isset($this->post) && isset($this->post->post_author) ) {
            return (int)$this->post->post_author;
        }
        return false ;
    }


    public function title() {
        if ( isset($this->post) && isset($this->post->post_title) ) {
            return $this->post->post_title;
        }

        return "";
    }

    public function getContent($isFormat = false): string {

        if ( isset($this->post) && isset($this->post->post_content) ) {
            $content = $this->post->post_content;
            if ($isFormat) {

            }
            return $content;
        }

        return "";
    }

    public function shortTitle() {
        if (isset($this->post) && isset($this->post->post_title)) {
            $title = $this->post->post_title;
            $limit = 50;

            // Если длина заголовка превышает лимит
            if (mb_strlen($title) > $limit) {
                // Обрезаем строку до 90 символов
                $trimmedTitle = mb_substr($title, 0, $limit);
                // Находим последний пробел в обрезанной строке
                $lastSpace = mb_strrpos($trimmedTitle, ' ');

                // Если пробел найден, обрезаем до него
                if ($lastSpace !== false) {
                    $title = mb_substr($trimmedTitle, 0, $lastSpace) . '...';
                } else {
                    // Если пробелов нет, просто обрезаем до лимита
                    $title = $trimmedTitle . '...';
                }
            }

            return $title;
        }

        return "";
    }

    public function saveTitle($title) {
        if ( isset($this->post) && isset($this->post->post_title) ) {
            $data = [
                'ID' => $this->post->ID,
                'post_title' => $title,
            ];

            wp_update_post( wp_slash( $data ) );
            return true;
        }

        return false;
    }

    public function getType() : string {
        if ( isset($this->post) && isset($this->post->post_type) ) {
            return $this->post->post_type;
        }

        return "";
    }

    public function saveAuthor($authorId) {
        if ( isset($this->post) && isset($this->post->post_title) ) {
            $data = [
                'ID' => $this->post->ID,
                'post_author' => $authorId,
            ];

            wp_update_post( wp_slash( $data ) );
            return true;
        }

        return false;
    }

    public function saveStatus($status) {
        if ( isset($this->post) && isset($this->post->post_title) ) {
            $data = [
                'ID' => $this->post->ID,
                'post_status' => $status,
            ];

            wp_update_post( wp_slash( $data ) );
            return true;
        }

        return false;
    }

    function getStatus() {
        return $this->post->post_status;
    }


    public function description() {
        return $this->post->post_content;
    }

    public function link() {
        return get_permalink($this->post->ID);
    }

    public function getEditLink() {
        return get_edit_post_link( $this->post->ID );
    }


    public function firstImage($size = "thumbnails") {
        $url = get_the_post_thumbnail_url( $this->post->ID, $size );

        if (!$url) {
            return false;
        }

        return $url;
    }

    public function thumbanilId() {
        return get_post_thumbnail_id($this->post->ID);
    }

    public function prop($name) {
        $value = get_post_meta($this->id(), $name, true);
        return $value;
    }

    public function saveProp($name, $value) {
        update_post_meta($this->id(), $name, $value);
    }

    public function getTags($taxonomyName) : array {
        $terms = get_the_terms($this->post->ID, $taxonomyName);

        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        $tags = [];
        foreach ($terms as $term) {
            $tags[$term->term_id] = $term->name;
        }

        return $tags;
    }

    public function getTagKeys($taxonomyName) : array {
        return array_keys($this->getTags($taxonomyName));
    }

    public function getTagKeysString($taxonomyName) : string {
        return implode(",", $this->getTagKeys($taxonomyName));
    }

    public function getTagValues($taxonomyName) : array {
        return array_values($this->getTags($taxonomyName));
    }

    public function getTagString($taxonomyName) : string {
        return implode(", ", $this->getTagValues($taxonomyName));
    }

    public function saveTag($taxonomyName, $term, $removeObjectTerms = true) {
        if (empty($taxonomyName)) {
            return false;
        }

        if (is_numeric($term)) {
            $term = (int)$term; // Приводим к целому числу
        }

        // Добавляем новый тег
        $result = wp_set_object_terms($this->post->ID, [$term], $taxonomyName);

        return !is_wp_error($result);
    }

    public function saveTags($taxonomyName, $termsString) {
        $terms = explode(",", $termsString);
        $newTerms = [];
        foreach ($terms as $term) {
            $term = trim($term);
            if (is_numeric($term)) {
                $term = (int)$term; // Приводим к целому числу
            }
            $newTerms[] = $term;
        }
        $result = wp_set_object_terms($this->post->ID, $newTerms, $taxonomyName);
        return !is_wp_error($result);
    }

    public function saveMediaFiles($fileName) {
        if (isset($_FILES[$fileName])) {
            $imageId = Utils::uploadImage($_FILES[$fileName], $this->id());
            if (!$imageId) {
                return $this->error_fields([$this->get_param("cases_thumbnail") => __("Файл не загрузился. Неверный формат")]);
            }

            return $imageId;
        }
    }

    static function getFieldValue($postId, $field) {
        if (strtoupper($field) == "ID") {
            return $postId;
        }

        $post = get_post($postId);
        if ($post && isset($post->{$field})) {
            return $post->{$field};
        }

        return "";

    }

    static function getMetaValue($postId, $field) {
        return get_post_meta($postId, $field, true);
    }

    public function getCountComments()
    {
        return get_comments_number($this->id());
    }

    function getRelatedPosts($count = 4) {
        // Получаем категории текущей записи
        $categories = get_the_category($this->id());

        if (empty($categories)) {
            return array();
        }

        // Получаем ID категорий
        $categoryIds = array();
        foreach ($categories as $category) {
            $categoryIds[] = $category->term_id;
        }

        // Аргументы для запроса похожих записей
        $args = array(
            'category__in' => $categoryIds,
            'post__not_in' => array($this->id()),
            'posts_per_page' => $count,
            'orderby' => 'rand', // Сортировка по случайному порядку
            'post_status' => 'publish'
        );

        // Выполняем запрос
        $relatedPosts = new WP_Query($args);

        return $relatedPosts->posts;
    }



}
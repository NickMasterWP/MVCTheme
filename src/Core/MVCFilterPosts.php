<?php

namespace MVCTheme\Core;

class MVCFilterPosts extends \WP_Query
{
    protected $args;
    protected $result;

    public function __construct()
    {
        $this->args = [];
        $this->result = [];
        parent::__construct();
    }

    function getArgs(): array {
        return $this->args;
    }

    function addParam(string $name, $value) {
        $this->args[$name] = $value;
        return $this;
    }

    function addTitle(string $value): static {
        $this->args["title"] = $value;
        return $this;
    }

    function setPostType(string $postType) {
        return $this->addParam("post_type", $postType);
    }

    function setPostPerPage(int $count) {
        return $this->addParam("posts_per_page", $count);
    }

    function setAuthor(int $userId) {
        return $this->addParam("author", $userId);
    }

    function setAuthors(array $usersId) {
        return $this->addParam("author__in", $usersId);
    }

    function setPaged(int $pageNumber) {
        return $this->addParam("paged", $pageNumber);
    }

    function setStatuses(array $statuses) {
        return $this->addParam("post_status", $statuses);
    }

    function setNotInPostId(int $postId) {
        return $this->addParam("post__not_in", [$postId]);
    }

    function setNotInIds(array $ids) {
        return $this->addParam("post__not_in", $ids);
    }

    function setNotInPostsId(array $postIds) {
        return $this->addParam("post__not_in", $postIds);
    }


    function execute() : array {
        $result = $this->query($this->args);
        return $result;
    }

    public function getCount() : int {
        return $this->found_posts ?? 0;
    }

    public function getCountPages() : int {
        return $this->max_num_pages ?? 0;
    }

    public function getLimit() : int {
        return $this->query_vars["posts_per_page"] ?? 0;
    }

    public function getCurrentPage() : int {
        return $this->query_vars["paged"] > 0 ? $this->query_vars["paged"] : 1;
    }

    /**
     * @param string $key
     * @param string|array $value
     * @param string $compare Оператор для проверки значения произвольного поля. Может быть: =, !=, >, >=, <, <=, LIKE, NOT LIKE, IN, NOT IN, BETWEEN, NOT BETWEEN, EXISTS (с версии 3.5), NOT EXISTS (3.5), REGEXP (3.7), NOT REGEXP (3.7) и RLIKE (3.7);
     * @param string $type Тип произвольного поля NUMERIC CHAR DATETIME DATE TIME
     * @param bool|null $orderName
     * @return $this
     */

    public function addMeta(string $key, string|array $value, string $compare = "=", string $type = "CHAR", string $orderName = null  ) {
        // Создаем новую часть для tax_query
        $metaQuery = [
            'key' => $key,
            'value'    => $value, // Фильтруем по ID термина
            'compare'    => $compare,    // Значения для фильтрации
            'type' => $type
        ];

        // Проверяем, существует ли уже tax_query
        if (!isset($this->args['meta_query'])) {
            // Если нет, инициализируем его как массив
            $this->args['meta_query'] = [];
        }

        // Добавляем новое условие в tax_query
        if ($orderName) {
            $this->args['meta_query'][$orderName] = $metaQuery;
        } else {
            $this->args['meta_query'][] = $metaQuery;
        }

        return $this; // Возвращаем $this для цепочки вызовов
    }

    public function addMetaMissingOrNotLike(string $key, string $value) {
        // Условие для отсутствия метаполя или его несоответствия значению
        $metaQuery = [
            'relation' => 'OR',
            [
                'key' => $key,
                'compare' => 'NOT EXISTS'
            ],
            [
                'key' => $key,
                'value' => $value,
                'compare' => 'NOT LIKE',
            ]
        ];

        if (!isset($this->args['meta_query'])) {
            $this->args['meta_query'] = [];
        }

        $this->args['meta_query'][] = $metaQuery;

        return $this;
    }

    public function addTaxonomy(string $nameTaxonomy, array $values) {
        $taxQuery = [
            'taxonomy' => $nameTaxonomy,
            'field'    => 'term_id',
            'terms'    => $values,
        ];

        if (!isset($this->args['tax_query'])) {
            // Если нет, инициализируем его как массив
            $this->args['tax_query'] = [];
        }

        // Добавляем новое условие в tax_query
        $this->args['tax_query'][] = $taxQuery;

        return $this; // Возвращаем $this для цепочки вызовов
    }

    public function getValueFilterTaxonomy($name) {
        if (isset($this->args['tax_query'])) {
            foreach ($this->args['tax_query'] as $tax) {
                if ($tax['taxonomy'] === $name) {
                    return $tax['terms'];
                }
            }
        }
        return []; // Возвращаем пустой массив, если не найдено
    }

    public function getValueAuthors(): array {
        // Проверяем, есть ли параметр 'author' в args
        if (isset($this->args['author'])) {
            return (array)$this->args['author']; // Возвращаем массив идентификаторов авторов
        }

        return []; // Если нет, возвращаем пустой массив
    }

    public function getValueAuthorsString(): string {
        if (isset($this->args['author__in']) ) {
            return implode(",", $this->args['author__in']);
        }
        if (isset($this->args['author'])) {
            return $this->args['author'];
        }
        return "";

    }

    public function setOrder($field, $order) {
        $this->addParam("orderby", $field);
        $this->addParam("order", $order);
        return $this;
    }

    function getSql() {
        return $this->request;
    }
}
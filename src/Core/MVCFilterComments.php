<?php

class MVCFilterComments extends WP_Comment_Query
{
    protected $args;
    protected $result;

    public function __construct()
    {
        $this->args = [];
        $this->result = [];
        parent::__construct();
    }

    function addParam(string $name, $value) {
        $this->args[$name] = $value;
        return $this;
    }

    function setPostId(int $postId) {
        return $this->addParam("post_id", $postId);
    }

    function setAuthorId(int $authorId) {
        return $this->addParam("user_id", $authorId);
    }

    function setAuthorEmail(string $email) {
        return $this->addParam("author_email", $email);
    }

    function setStatus(string $status) {
        return $this->addParam("status", $status);
    }

    function setOrderBy(string $field) {
        return $this->addParam("orderby", $field);
    }

    function setOrder(string $order) {
        return $this->addParam("order", $order);
    }

    function setNumber(int $number) {
        return $this->addParam("number", $number);
    }

    function setPaged(int $pageNumber) {
        return $this->addParam("paged", $pageNumber);
    }

    public function addMeta(string $key, string $value, string $compare, string $type = "CHAR", string $orderName = null  ) {
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


    function execute() : array {
        $this->result = $this->query($this->args);
        return $this->result;
    }

    public function getCount() : int {
        return count($this->result);
    }

    public function getQueryArgs() : array {
        return $this->args;
    }
}
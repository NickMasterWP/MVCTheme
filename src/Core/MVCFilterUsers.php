<?php

class MVCFilterUsers extends WP_User_Query
{
    protected $args;
    protected $result;

    public function __construct()
    {
        $this->args = [];
        $this->args['fields'] = 'all';
        parent::__construct($this->args);
    }

    function addParam(string $name, $value) {
        $this->args[$name] = $value;
        return $this;
    }

    function setRole(string $role) {
        return $this->addParam('role', $role);
    }

    function setRoles(array $roles) {
        return $this->addParam('role__in', $roles);
    }

    function setUserId(int $userId) {
        return $this->addParam('include', [$userId]);
    }

    function setUserIds(array $userIds) {
        return $this->addParam('include', $userIds);
    }

    function setPaged(int $pageNumber) {
        return $this->addParam('paged', $pageNumber);
    }

    function setNumber(int $number) {
        return $this->addParam('number', $number);
    }

    /**
     * @param string $key
     * @param string|array $value
     * @param string $compare Оператор для проверки значения произвольного поля. Может быть: =, !=, >, >=, <, <=, LIKE, NOT LIKE, IN, NOT IN, BETWEEN, NOT BETWEEN, EXISTS (с версии 3.5), NOT EXISTS (3.5), REGEXP (3.7), NOT REGEXP (3.7) и RLIKE (3.7);
     * @param string $type Тип произвольного поля NUMERIC CHAR DATETIME DATE TIME
     * @param bool|null $orderName
     * @return $this
     */

    public function addMeta(string $key, string|array $value, string $compare, string $type = "CHAR", string $orderName = null  ) {
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

    function execute(): array {
        $this->prepare_query($this->args);
        $this->query();

        return $this->results; // Возвращаем результат запроса
    }

    public function getCount(): int {
        return count($this->results);
    }

    public function getCountPages(): int {
        $perPage = $this->args['number'] ?? get_option('posts_per_page'); // Значение по умолчанию
        $totalUsers = $this->get_total(); // Количество всех пользователей
        return (int) ceil($totalUsers / $perPage);
    }

    public function getLimit(): int {
        return $this->args['number'] ?? get_option('posts_per_page'); // Значение по умолчанию
    }

    public function getCurrentPage(): int {
        return isset($this->args['paged']) ? (int) $this->args['paged'] : 1;
    }

    public function getValueRoles(): array {
        return $this->args['role__in'] ?? [];
    }

    public function getValueUserIds(): array {
        return $this->args['include'] ?? [];
    }

    public function getSqlQuery() {
        return $this->request;
    }
}
<?php

namespace MVCTheme\Traits;

trait MVCCustomColumnsTrait {

    private $customColumns = [];

    public function addCustomColumn($postType, $columnName, $columnTitle, $func, $number = false) {
        if (!isset($this->customColumns[$postType])) {
            $this->customColumns[$postType] = [];
        }
        $this->customColumns[$postType][$columnName] = [
            "columnTitle" => $columnTitle,
            "func" => $func,
            "number" => $number
        ];
    }

    public function customColumnHeaders($columns) {
        $postType = $_REQUEST["post_type"] ?? "post";
        if ($postType && isset($this->customColumns[$postType])) {
            foreach ($this->customColumns[$postType] as $columnName => $params) {
                $columns[$columnName] = $params["columnTitle"];
            }
        }
        return $columns;
    }

    public function customColumnContent($column, $postId) {
        $postType = $_REQUEST["post_type"] ?? "post";
        if ($postType && isset($this->customColumns[$postType][$column])) {
            $func = $this->customColumns[$postType][$column]["func"];
            if (is_callable($func)) {
                $result = call_user_func($func, $postId, $column);
                echo esc_html($result);
            }
        }
    }

    public function customColumnInit() {
        foreach ($this->customColumns as $postType => $columns) {
            add_filter("manage_{$postType}_posts_columns", [$this, 'customColumnHeaders']);
            add_action("manage_{$postType}_posts_custom_column", [$this, 'customColumnContent'], 10, 2);
        }
    }

}
<?php

namespace MVCTheme\Core;

class MVCListTableAdmin extends \WP_List_Table
{

    public function __construct(protected $name, protected $title = "") {
        parent::__construct(
            array(
                'plural'   => $name,
                'singular' => $name,
                'ajax'     => true,
                'screen'   => $name,
            )
        );
    }

    function title() {
        return $this->title;
    }

    function table()  {
    }

    function editForm($id) : string  {
        return "";
    }

    function delete($id)  {
    }

    function getPageName() {
        return $this->name;
    }

    function getFields() : array {
        return [];
    }

    function hasAddButton() {
        return false;
    }

    function hasSearch() {
        return false;
    }

    function getFilterFields() : array {
        return [];
    }

    function getFilterValue($name) {
        return $_REQUEST[$name] ?? null;
    }

    function run() {

        if (isset($_REQUEST["action"])) {
            switch ($_REQUEST["action"]) {
                case "edit":
                    echo MVCView::admin("list-table/edit", [
                        "table" => $this,
                        "id" => $_REQUEST["id"]
                    ]);
                    return;
                case "delete":
                    $this->delete($_REQUEST["id"]);
                    break;
            }
        }

        $this->table();
        echo MVCView::admin("list-table/table", [
            "table" => $this,
            "hasAddButton" => $this->hasAddButton(),
            "hasSearch" => $this->hasSearch(),
            "hasFilter" => count($this->getFilterFields()) > 0,
        ]);

    }

    // Колонки по умолчанию
    public function get_columns() {
        $fields = $this->getFields();
        $res = [];
        if (isset($fields["id"])) {
            $res['cb'] = '<input type="checkbox" />';
        }

        foreach ($fields as $field) {
            $res[$field["name"]] = $field["title"];
        }

        return $res;
    }

    public function get_sortable_columns() {
        $fields = $this->getFields();

        $res = [];
        foreach ($fields as $field) {
            $res[$field["name"]] = [$field["name"], $field["sort"] ?? false];
        }

        return $res;
    }

    function getPageNumber() {
        return $this->get_pagenum();
    }

    function setItems($items) {
        $this->items = $items;
    }

    public function column_default($item, $column_name) {
        return isset($item[$column_name]) ? $item[$column_name] : '';
    }

    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="game[]" value="%s" />', $item['id']);
    }


    // Дополнительные действия для строки
    public function column_name($item) {
        if (isset($item['id'])) {
            $actions = [
                'edit'   => sprintf('<a href="?page=%s&action=%s&item=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']),
                'delete' => sprintf('<a href="?page=%s&action=%s&item=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
            ];

            return sprintf('%s %s', $item['name'], $this->row_actions($actions));
        }

        return '';
    }

    public function __extra_tablenav($which) {
        if ($which == 'top') {
            ?>
            <div class="alignleft actions">
                <label for="filter-name">Filter by Name:</label>
                <input type="text" name="filter_name" id="filter-name" value="<?php echo isset($_REQUEST['filter_name']) ? esc_attr($_REQUEST['filter_name']) : ''; ?>">

                <label for="filter-score-min">Min Score:</label>
                <input type="number" name="filter_score_min" id="filter-score-min" value="<?php echo isset($_REQUEST['filter_score_min']) ? intval($_REQUEST['filter_score_min']) : ''; ?>">

                <input type="submit" name="filter_action" class="button" value="Filter">
                <a href="<?php echo admin_url('admin.php?page=crm-game'); ?>" class="button">Reset</a>
            </div>
            <?php
        }
    }

    function getUrlEdit($id) {
        return admin_url('admin.php?page='.$this->getPageName()."&action=edit&id=".$id);
    }

    function getLinkEdit($id, $name) {
        return "<a href='".$this->getUrlEdit($id)."'>".$name."</a>";
    }

    public function beforeTable() {

    }

    public function afterTable() {

    }
}
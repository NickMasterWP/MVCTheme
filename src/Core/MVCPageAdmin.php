<?php

namespace MVCTheme\Core;

class MVCPageAdmin
{

    function __construct(protected $name, protected $title = "") {
    }

    function delete($id): void
    {

    }

    function run(): void
    {

        if (isset($_REQUEST["action"])) {
            switch ($_REQUEST["action"]) {
                case "edit":
                    echo MVCView::admin($this->name."/edit", [
                        "table" => $this,
                        "id" => $_REQUEST["id"]
                    ]);
                    return;
                case "delete":
                    $this->delete($_REQUEST["id"]);
                    break;
            }
        }

        echo MVCView::admin($this->name."/index", $this->getParams());

    }

    protected function getParams(): array
    {
        return [];
    }

}
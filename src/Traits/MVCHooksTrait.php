<?php

namespace MVCTheme\Traits;

trait MVCHooksTrait {

    private $actionHooks = [];
    private $filterHooks = [];

    public function addActionHook($tag, $module, $priority = 10, $accepted_args = 1) {
        $this->actionHooks[$tag] = [
            "module" => $module,
            "priority" => $priority,
            "accepted_args" => $accepted_args
        ];
    }

    public function addFilterHook($tag, $module, $priority = 10, $accepted_args = 1) {
        $this->filterHooks[$tag] = [
            "module" => $module,
            "priority" => $priority,
            "accepted_args" => $accepted_args
        ];
    }

    public function actionHooksInit() {
        foreach ($this->actionHooks as $actionName => $params) {
            $moduleFileName = $params["module"];
            $fileAction = $this->getThemeChildFilePath("includes/controller/hooks/actions/" . $moduleFileName . ".php");

            if (file_exists($fileAction)) {
                include_once $fileAction;
                add_action($actionName, "$moduleFileName::run", $params["priority"], $params["accepted_args"]);
            }
        }
    }

    public function actionFiltersInit() {
        foreach ($this->filterHooks as $filterName => $params) {
            $moduleFileName = $params["module"];
            $fileFilter = $this->getThemeChildFilePath("includes/controller/hooks/filters/" . $moduleFileName . ".php");

            if (file_exists($fileFilter)) {
                include_once $fileFilter;
                add_filter($filterName, "$moduleFileName::run", $params["priority"], $params["accepted_args"]);
            }
        }
    }

}
<?php

namespace MVCTheme\Traits;

trait MVCAjaxTrait {

    private $ajaxActions = [];

    public function addAjaxAction($moduleFileName, $ajaxActionName, $role = false, $type = 3): void {
        $this->ajaxActions[$ajaxActionName] = [
            "module" => $moduleFileName,
            "type" => $type,
            "role" => $role
        ];
    }

    public function registerAjaxAction(): void {
        foreach ($this->ajaxActions as $ajaxActionName => $param) {
            $moduleFileName = $param["module"];
            $role = $param["role"];
            $type = $param["type"];

            $fileAction = $this->getThemeChildFilePath("app/Controller/Ajax/" . $moduleFileName . ".php");

            if (file_exists($fileAction)) {
                include_once $fileAction;
                $actionInstance = new $moduleFileName($ajaxActionName, $type, $role);
            } else {
                echo $fileAction." not found";
            }
        }
    }
}
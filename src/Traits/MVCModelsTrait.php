<?php

namespace MVCTheme\Traits;

trait MVCModelsTrait {

    private $models = [];

    function addModel($name) {
        $this->models[] = $name;
    }

    function registerModels() {
        foreach ($this->models as $modelName) {
            $fileModel = $this->getThemeChildFilePath("app/Model/".$modelName.".php");

            if (file_exists($fileModel)) {
                include_once $fileModel;
            }
        }
    }

    function updateBase() {
        foreach($this->models as $model) {
            if (class_exists($model) && is_subclass_of($model, 'MVCBaseModelBD')) {
                $table = new $model();
                if (method_exists($table, 'update_table')) {
                    $table->update_table();
                }
            }
        }

    }
}

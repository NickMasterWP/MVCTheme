<?php

namespace MVCTheme\Traits;

use MVCTheme\MVCTheme;

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
        $MVCTheme = MVCTheme::getInstance();

        foreach($this->models as $model) {
            $MVCTheme->debug(sprintf("Update base: model[%s];", $model));
            if (!class_exists($model)) {
                $MVCTheme->debug(sprintf("Class not found: class[%s];", $model));
                continue;
            }
            if (!is_subclass_of($model, 'MVCTheme\Core\MVCBaseModelBD')) {
                $parentClass = get_parent_class($model);
                $MVCTheme->debug(sprintf("Class not subclass MVCBaseModelBD: class[%s]; parentClass[%s]", $model, $parentClass));
                continue;
            }

            $table = new $model();
            if (method_exists($table, 'update_table')) {
                $MVCTheme->debug(sprintf("Update table: model[%s];", $model));
                $table->update_table();
            }
        }

    }
}

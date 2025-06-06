<?php

namespace MVCTheme\Traits;

trait MVCRestApiTrait {

    private $restApi = [];

    function initializeRestApi() {
        add_action( 'rest_api_init', array($this, 'registerRestApi'), 1000 );
    }

    function addRestApi($namespace, $route, $methods, $callback, $permissionCallback = '__return_true') {
        $this->restApi[] = [
            "namespace" => $namespace,
            "route" => $route,
            "methods" => $methods,
            "callback" => $callback,
            "permissionCallback" => $permissionCallback
        ];

    }

    function registerRestApi() {

        foreach ($this->restApi as $settings) {

            $className = $settings["callback"];

            $fileApi = $this->getThemeChildFilePath("app/Controller/Api/".$className.".php");

            $controller = null;
            if (file_exists($fileApi)) {
                include_once $fileApi;
                $controller = new $className();
            }

            if ($controller) {
                register_rest_route(
                    $settings["namespace"],
                    $settings["route"],
                    array(
                        'methods' => $settings["methods"],
                        'callback' => [$controller, "run"],
                        'permission_callback' => $settings["permissionCallback"]
                    )
                );
            }

        }
    }

}

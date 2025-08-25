<?php

namespace MVCTheme\Traits;

use MVCTheme\MVCTheme;

trait MVCUrlHandlerTrait {

    private $urlHandler = [];

    function addUrlHandler(string $url, $handler, bool $isStrict = false, string $viewPath = "") {
        $this->urlHandler[] = [
            "url" => $url,
            "handler" => $handler,
            "isStrict" => $isStrict,
            "viewPath" => $viewPath
        ];
    }

    function urlHandlers() {
        return $this->urlHandler;
    }

    protected function locateControllerFile($MVCTheme, $controllerFile) {
        $locations = [
            $MVCTheme->getThemeChildFilePath("app/".$controllerFile),
            $MVCTheme->getThemeParentFilePath($controllerFile),
            __DIR__ . "/../" . $controllerFile
        ];

        foreach ($locations as $location) {
            if (is_file($location)) {
                return $location;
            }
        }

        return null;
    }

    function runUrlHandlers(): void {

        $requestUri = $_SERVER["REQUEST_URI"];
        $MVCTheme = MVCTheme::getInstance();

        foreach ($MVCTheme->urlHandlers() as $itemUrl) {
            if (
                ( $itemUrl["isStrict"] && $requestUri == $itemUrl["url"] ) ||
                ( !$itemUrl["isStrict"] && strpos( $requestUri, $itemUrl["url"]) === 0   )
            ) {

                $handler = $itemUrl["handler"];
                if (isset($handler[0]) && isset($handler[1]) ) {
                    $controllerName = $handler[0];
                    $actionMethod = $handler[1];


                    $pathController = "Controller/UrlHandler/";
                    $controllerFile = $pathController . $handler[0] . ".php";
                    $controllerPathFile = $this->locateControllerFile($MVCTheme, $controllerFile);

                    include_once $controllerPathFile;

                    if (!class_exists($controllerName)) {
                        continue;
                    }

                    $controller = new $controllerName();
                    if (!method_exists($controller, $actionMethod)) {
                        continue;
                    }

                    header("HTTP/1.1 200 OK");
                    $controller->{$actionMethod}();

                    break;
                }

            }
        }
    }

    function redirect($url): void
    {
        header('Location: '.$url);
        exit();
    }
}
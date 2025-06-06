<?php

namespace MVCTheme\Traits;

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

}
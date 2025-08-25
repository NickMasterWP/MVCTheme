<?php

namespace MVCTheme\Core;


use MVCTheme\Controller\Template\NotFoundController;
use MVCTheme\MVCTheme;

class MVCRouter {
	
	protected $controller;
	protected $action;
	protected $params;
	protected $pathController;
    /**
     * @var mixed|null
     */
    protected $viewPath;

    function __construct() {
		 
		$controller = "NotFound";
		$action = "index";
		$params = $_GET;
        $viewPath = null;

        $MVCTheme = MVCTheme::getInstance();

		if ( is_front_page() ) {
			$controller = "index";  
		} else if (  is_page() ) {
            $controller = "page";
        } else if ( is_singular() ) {
            $controllerSingle = null;
            foreach ($MVCTheme->getPostTypes() as $postType => $values) {
                if (is_singular($postType)) {
                    $controllerSingle = $postType;
                    break;
                }
            }
			$controller = $controllerSingle ?? "single";
		} else if ( is_author() ) {
            $controller = "author";
        } else if ( is_search() ) {
			$controller = "search";  
		} else if ( is_archive() || is_home() ) {
            $controller = "archive";
        } else if ( is_404() ) {
			$controller = "404";  
		}

		$this->controller = $controller;
		$this->action = $action;
		$this->params = $params;
        $this->viewPath = $viewPath;
		
		$this->pathController = "Controller/Template/";
	}

    function controller() {
        // Проверка на невалидный контроллер
        if (empty($this->controller) || $this->controller === "NotFound") {
            return $this->controller404();
        }

        $MVCTheme = MVCTheme::getInstance();
        $controllerName = ucfirst($this->controller) . "Controller";
        $controllerFile = $this->pathController . $controllerName . ".php";

        // Поиск файла контроллера в определенном порядке
        $controllerPathFile = $this->locateControllerFile($MVCTheme, $controllerFile);

        if ($controllerPathFile === null || !is_file($controllerPathFile)) {
            return $this->controller404();
        }

        include_once $controllerPathFile;

        if (!class_exists($controllerName)) {
            return $this->controller404();
        }

        try {
            $controller = new $controllerName($this->action);

            // Проверка существования метода действия
            $actionMethod = $this->action . "Action";
            if (!method_exists($controller, $actionMethod)) {
                return $this->controller404();
            }

            header('HTTP/1.1 200 OK');

            if ($this->viewPath) {
                $controller->setViewPath($this->viewPath);
            }

            return $controller;
        } catch (Exception $e) {
            // Логирование ошибки при создании контроллера
            error_log("Controller initialization error: " . $e->getMessage());
            return $this->controller404();
        }
    }

    /**
     * Поиск файла контроллера в различных локациях
     */
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
	
	function controller404() {
		return new NotFoundController();
	}
	
	function controller500() {
		
	}
	
}
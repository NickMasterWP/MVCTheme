<?php

namespace MVCTheme\Core;

use MVCTheme\MVCTheme;

class MVCView {

    static function pathChild($path) : string {
        $MVCTheme = MVCTheme::getInstance();
        return $MVCTheme->getThemeChildFilePath($path);
    }

    static function pathParent($path) : string {
        $MVCTheme = MVCTheme::getInstance();
        return $MVCTheme->getThemeChildFilePath($path);
    }

    static function pathCore($path) : string {
        return __DIR__."/../".$path;
    }

    static function content($pathView, $content = "", $args = []) {
        $args = (array)$args;

        // Более безопасная альтернатива extract()
        foreach ($args as $key => $value) {
            ${$key} = $value;
        }

        $pathView = 'assets/view/'.$pathView;
        $paths = [
            self::pathChild($pathView),
            self::pathParent($pathView),
            self::pathCore($pathView)
        ];

        $foundPath = null;
        foreach ($paths as $path) {
            if (is_file($path)) {
                $foundPath = $path;
                break;
            }
        }

        if ($foundPath === null) {
            return "Файл не найден '$pathView'";
        }

        ob_start();
        include $foundPath;
        $content = ob_get_clean();
        return $content;
    }
	
	static function layout($name, $params_view = []) {
		$path_layout = "layouts/".$name.".php";
		return MVCView::content($path_layout, "", $params_view);
	}
	
	static function partial($path, $params_view = []) { 
		$path_layout = $path.".php";
		return MVCView::content($path_layout, "", $params_view);
	}
	
	static function controller($name, $action, $params_view = []) { 

		$path_layout = $name."/".$action.".php";

		return MVCView::content($path_layout, "", $params_view);
	}
	
	static function page($content, $params_view = []) { 
		
		$path_page = "index.php";
		return MVCView::content($path_page, $content, $params_view);
	}

    static function email($name, $params_view = []) {
        $path_layout = "email/".$name.".php";
        return MVCView::content($path_layout, "", $params_view);
    }
    static function admin($name, $params_view = []) {
        $path_layout = "admin/".$name.".php";
        return MVCView::content($path_layout, "", $params_view);
    }
    static function adminPart($name, $params_view = []) {
        $path_layout = "admin/part/".$name.".php";
        return MVCView::content($path_layout, "", $params_view);
    }
    static function form($name, $params_view = []) {
        $path_layout = "part/form/".$name.".php";
        return MVCView::content($path_layout, "", $params_view);
    }

    public static function pageTemplate($name, $params_view) {
        $path_layout = "page-template/".$name.".php";
        return MVCView::content($path_layout, "", $params_view);
    }


}
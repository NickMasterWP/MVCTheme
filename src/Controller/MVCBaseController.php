<?php

namespace MVCTheme\Controller;

use MVCTheme\Core\MVCMenu;
use MVCTheme\Core\MVCSetting;
use MVCTheme\Core\MVCView;

class MVCBaseController {

    protected $activeAction;
    public $views;
    protected $displayType;
    protected $viewPath;

    function __construct($action = "index") {
        $this->activeAction = $action;
        $this->setDisplayPage();
        $this->views = json_decode("{}");
        $this->setTitle("");
        $this->setDescription("");
        $this->setBreadcrumbs("");
        $this->viewPath = null;
    }

    function indexAction() {

    }

    function setTitle($title) {
        $this->views->title = $title;
    }

    function setDescription($description) {
        $this->views->description = $description;
    }

    function setH1($h1) {
        $this->views->h1 = $h1;
    }

    function setDisplayPage() {
        $this->displayType = "page";
    }

    function setDisplayJson() {
        $this->displayType = "json";
    }

    function setDisplayMsg() {
        $this->displayType = "msg";
    }

    function setDisplayAction() {
        $this->displayType = "action";
    }

    function setBreadcrumbs() {
        $breadcrumbs = [];
        $breadcrumbs = apply_filters('get_breadcrumbs_array', $breadcrumbs);
        $this->views->breadcrumbs = $breadcrumbs;
    }

    function setViewPath($path) {
        $this->viewPath = $path;
    }

    function content() {
        $classNameParts  = explode('\\', get_class($this));
        $className = end($classNameParts );
        $className = strtolower(str_replace("Controller", "", $className));
        if ($this->viewPath) {
            return MVCView::partial($this->viewPath, $this->views);
        }
        return MVCView::controller($className, $this->activeAction, $this->views);
    }

    final public function view() {
        $action = $this->activeAction."Action";

        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            echo "method not found: $action";
            return;
        }

        $content = "";
        if ($this->displayType == "page") {
            $setting = new MVCSetting();
            $this->views->setting = $setting->getData();

            $menu = new MVCMenu();
            $this->views->menu = $menu->getData();

            $content = $this->content();
            $content = MVCView::page($content, $this->views);
        }

        if ($this->displayType == "action") {
            $content = $this->content();
        }

        echo $content;
    }
}
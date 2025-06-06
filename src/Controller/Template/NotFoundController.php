<?php

namespace MVCTheme\Controller\Template;

use MVCTheme\Controller\Base\MVCBaseController;

class NotFoundController  extends MVCBaseController {

    function indexAction() {

        header("HTTP/1.0 404 Not Found");

        $this->setTitle("404! Страница не найдена");
        $this->setDescription("Страница не найдена");
        $this->setH1("404! Страница не найдена");
    }

}
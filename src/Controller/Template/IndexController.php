<?php

use MVCTheme\Controller\MVCBaseController;

class IndexController extends MVCBaseController {
	
	function indexAction() {
		$this->setTitle("Главная");
		$this->setDescription("Главная описание");

	}
	 
}
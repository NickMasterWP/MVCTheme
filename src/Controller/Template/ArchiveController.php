<?php

use MVCTheme\Controller\MVCBaseController;

class ArchiveController extends MVCBaseController {
	
	function indexAction() {
		$this->setTitle("Главная");
		$this->setDescription("Главная описание");
	}
	 
}
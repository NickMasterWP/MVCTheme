<?php

use MVCTheme\Controller\MVCBaseController;

class SearchController extends MVCBaseController {
	
	function indexAction() {
		$this->setTitle("Поиск");
		$this->setDescription("Поиск");
	}
	 
}
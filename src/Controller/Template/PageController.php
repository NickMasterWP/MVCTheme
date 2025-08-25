<?php

use MVCTheme\Controller\MVCBaseController;

class PageController extends MVCBaseController {
	
	function indexAction() {
        if (class_exists('Elementor\Plugin')) {
            $this->views->isElementor =  \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID());
        } else {
            $this->views->isElementor = false;
        }

    }
	 
}
<?php 

namespace MVCTheme\Core;

use MVCTheme\MVCTheme;

class MVCSetting {

    static function getData($args = []) {

        $MVCTheme = MVCTheme::getInstance();
        $result = (object)$MVCTheme->getOptions();
        $result->siteTitle = get_bloginfo( "name" );
        $result->siteDescription = get_bloginfo( "description" );

        return $result;

    }
	
}   
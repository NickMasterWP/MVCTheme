<?php 

namespace MVCTheme\Core;

use MVCTheme\MVCTheme;

class MVCMenu {

		function getData($args = []) {
			$MVCTheme = MVCTheme::getInstance();
			$menus = (object)$MVCTheme->getMenus();
			
			$result = [];
			$result_menu = [];
			foreach($menus as $menu => $title) {
				$three_menu = $this->getItemsTreeMenu($menu);
				if (!isset($three_menu[0])) {
					continue;
				}
				foreach($three_menu[0] as $index => $menu_item) { 
					$menu_item->items = $this->getItems($menu_item->id, $three_menu);
				 	$result_menu[$menu][] = $menu_item;
				}
			}

			return (object)$result_menu;
		}
	
		function getItems($parent, $menu_array) {
			$result_menu = [];
			if (isset($menu_array[$parent]) ) {
				foreach($menu_array[$parent] as $index => $menu_item) {  
					$menu_item->items = $this->getItems($menu_item->id, $menu_array);
				 	$result_menu[] = $menu_item; 
				} 
			}
			return $result_menu;
		}
	
		function getItemsTreeMenu($location) {
			if (has_nav_menu($location)) {
                global $MVCTheme;
                $menu_field = $MVCTheme->menuField();

				$locations = get_nav_menu_locations() ;
				$m_items = array();

				if( isset($locations[ $location ]) ){
					$menu = wp_get_nav_menu_object( $locations[ $location ] ); // получаем ID
					$menu_items = wp_get_nav_menu_items( $menu ); // получаем элементы меню

					_wp_menu_item_classes_by_context( $menu_items );

					foreach ( (array) $menu_items as $key => $menu_item ){
						$parent = $menu_item->menu_item_parent;
						if (!$parent) {
							$parent = 0;
						}     
						if ( array_search("current-menu-item", $menu_item->classes) !== FALSE ) {
							$menu_item->classes[] = "active";
						}
						$class_dop = get_post_meta( $menu_item->ID, '_menu_item_classes', true );
						if (!empty($class_dop) ) { 
							//var_dump( implode(" ", $class_dop) );
							$menu_item->classes[] = implode(" ", $class_dop);
						} 

						$args_item = [
                            "id" => $menu_item->ID,
                            "name" => $menu_item->title,
                            "url"  =>  $menu_item->url,
                            "classes"  => implode(" ", $menu_item->classes)
                        ];

                        if (is_array($menu_field)) {
                            foreach ($menu_field as   $field => $setting) {
                                $args_item[$field] = get_post_meta( $menu_item->ID, '_menu_'.$field, true );
                            }
                        }

						$item = (object)$args_item;
						$m_items[$parent][] = $item; 
					}  
					return $m_items;
				} 
			}

				return array();
		}
}   
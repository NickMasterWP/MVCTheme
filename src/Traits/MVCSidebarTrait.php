<?php

namespace MVCTheme\Traits;

trait MVCSidebarTrait {

    private $sidebars = [];

    function initializeSidebars() {
        add_action( 'init', array($this, 'registerSideBars'), 1000 );
    }


    function addSideBar($name, $id ) {
        $this->sidebars[$id] = $name;
    }

    function registerSideBars() {

        foreach ($this->sidebars as $id => $name) {
            register_sidebar( array(
                'name'          => $name,
                'id'            => $id,
                'description'   => '',
                'before_widget' => '<aside id="%1$s" class="sidebar__widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="sidebar__title">',
                'after_title'   => '</h2>',
            ) );
        }
    }

    function printSidebar($id) {
        dynamic_sidebar( $id );
    }
}

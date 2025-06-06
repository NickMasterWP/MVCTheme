<?php

namespace MVCTheme\Traits;

use MVCTheme\Core\MVCSetting;

trait MVCSettingThemeTrait {

    var $version = '1.0.0';

    public function setVersion($version)
    {
        $this->version = $version;
    }

    private function getVersion() {
        return $this->version;
    }

    static function errorReporting($active = false) {
        if ($active) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    function init() {

        //add_image_size( 'thumbnail-product', 50, 50, false );

        add_theme_support( 'post-thumbnails' );
        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontEnqueueScripts'));

        if (!$this->adminPanelUser) {
            if (!current_user_can('administrator') && !current_user_can('editor') && !wp_doing_ajax() ) {
                show_admin_bar(false);
            }
        }
    }

    function switchTheme( $old_name, $old_theme ){
        $this->registerPostTypes();
        flush_rewrite_rules();
        $this->updateBase();
        $this->initRoles();
    }


    function adminLogo() {
        $settingSite = MVCSetting::getData();
        echo "<link rel='stylesheet' id='login-mvc-css'  href='".THEME_URL."assets/css/login.css' type='text/css' media='all' />";
        if (isset($settingSite->logo)) {
            echo  '<style type="text/css">#login h1 a { background: url(' . $settingSite->logo . ') no-repeat  !important;  }</style>';
        };

        unset($setting_site);

    }


    function adminInit() {
        if (!$this->adminPanelUser) {
            if (!current_user_can('administrator') && !current_user_can('editor') && !wp_doing_ajax() ) {
                wp_redirect(site_url());
                die();
            }
        }
    }

    function uploadMimes( $mimes ) {
        $mimes['svg']  = 'image/svg+xml';
        return $mimes;
    }


    function fixSvgMimeType( $data, $file, $filename, $mimes, $real_mime = '' ) {

        if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) )
            $dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
        else
            $dosvg = ( '.svg' === strtolower( substr($filename, -4) ) );

        if( $dosvg ){

            if( current_user_can('manage_options') ){
                $data['ext']  = 'svg';
                $data['type'] = 'image/svg+xml';
            }
            else {
                $data['ext'] = $type_and_ext['type'] = false;
            }

        }

        return $data;
    }

    function showSvgInMediaLibrary( $response ) {

        if ( $response['mime'] === 'image/svg+xml' ) {

            // С выводом названия файла
            $response['image'] = [
                'src' => $response['url'],
            ];
        }

        return $response;
    }

    function setName($name): void {
        $this->name = $name;
    }
}
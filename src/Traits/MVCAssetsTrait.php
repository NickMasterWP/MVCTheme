<?php

namespace MVCTheme\Traits;

trait MVCAssetsTrait {
    private $styles = [];
    private $scripts = [];
    private $adminStyle = [];
    private $adminScript = [];

    function getThemeChildFilePath($filePath) {
        return get_theme_file_path($filePath );
    }

    function getThemeParentFilePath($filePath) {
        return get_parent_theme_file_path($filePath );
    }

    function getThemeChildFileURL($filePath) {
        return get_theme_file_uri($filePath );
    }

    function getThemeParentFileURL($filePath) {
        return get_parent_theme_file_uri($filePath );
    }

    function getMVCThemeFileURL($filePath) {
        $vendorPath = get_theme_file_path() . '/includes/vendor/masterwp/mvctheme/src/' . $filePath;

        if (file_exists($vendorPath)) {
            return get_theme_file_uri() . '/includes/vendor/masterwp/mvctheme/src/' . $filePath;
        }

        return get_parent_theme_file_uri($filePath);
    }

    public function addStyleFile($name, $path) {
        $this->styles[$name] = $path;
    }

    public function addScriptFile($name, $path) {
        $this->scripts[$name] = $path;
    }

    public function addStyleFileAdmin($name, $src) {
        $this->adminStyle[$name] = $src;
    }

    public function addScriptFileAdmin($name, $src) {
        $this->adminScript[$name] = $src;
    }

    public function adminEnqueueScripts() {
        wp_enqueue_script('form-ajax', $this->getMVCThemeFileURL('assets/js/lib/formajax.js'), [], $this->getVersion(), true);
        wp_enqueue_script('mvc-loader', $this->getMVCThemeFileURL('assets/js/lib/mvc-loader.js'), [], $this->getVersion(), true);
        wp_enqueue_style('style-admin-css', $this->getMVCThemeFileURL('assets/css/admin/style.css'), [], $this->getVersion());
        wp_enqueue_script('repeater-meta-box', $this->getMVCThemeFileURL('assets/js/admin/repeater-meta-box.js'), [], $this->getVersion(), true);
        wp_enqueue_style('repeater-meta-box-css', $this->getMVCThemeFileURL('assets/css/admin/repeater-meta-box.css'), [], $this->getVersion());

        foreach ($this->adminStyle as $name => $src) {
            wp_enqueue_style($name, $src, [], $this->getVersion());
        }
        foreach ($this->adminScript as $name => $src) {
            wp_enqueue_script($name, $src, [], $this->getVersion());
        }

        wp_localize_script('jquery', 'mvc_setting', ['mvc_ajaxurl' => admin_url('admin-ajax.php')]);
    }

    public function frontEnqueueScripts() {
        wp_dequeue_style("dns-prefetch");
        wp_dequeue_style("classic-theme-styles");

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_resource_hints', 2);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');

        foreach ($this->styles as $name => $path) {
            wp_enqueue_style($name, $path, [], $this->getVersion());
        }
        foreach ($this->scripts as $name => $path) {
            wp_enqueue_script($name, $path, [], $this->getVersion());
        }

        wp_localize_script('jquery', 'mvc_setting', ['mvc_ajaxurl' => admin_url('admin-ajax.php')]);
    }

    public function getUrl($path) : string
    {
        return home_url($path);
    }
}
<?php

namespace MVCTheme;

use MVCTheme\Core\MVCElementorExtension;
use MVCTheme\Traits\MVCCronTrait;
use MVCTheme\Traits\MVCElementorTrait;
use MVCTheme\Traits\MVCMenuAdminPanelTrait;
use MVCTheme\Traits\MVCModelsTrait;
use MVCTheme\Traits\MVCPostTypeTrait;
use MVCTheme\Traits\MVCTaxonomyTrait;
use MVCTheme\Traits\MVCCustomColumnsTrait;
use MVCTheme\Traits\MVCMetaBoxesTrait;
use MVCTheme\Traits\MVCUrlHandlerTrait;
use MVCTheme\Traits\MVCUserFieldsTrait;
use MVCTheme\Traits\MVCAssetsTrait;
use MVCTheme\Traits\MVCRoleTrait;
use MVCTheme\Traits\MVCShortcodeTrait;
use MVCTheme\Traits\MVCMenuTrait;
use MVCTheme\Traits\MVCAjaxTrait;
use MVCTheme\Traits\MVCHooksTrait;
use MVCTheme\Traits\MVCCustomizerTrait;
use MVCTheme\Traits\MVCRestApiTrait;
use MVCTheme\Traits\MVCSidebarTrait;
use MVCTheme\Traits\MVCMetaBoxesCommentsTrait;
use MVCTheme\Traits\MVCSettingThemeTrait;


if (!defined('ABSPATH')) exit;

class MVCTheme {

    private static ?MVCTheme $instance = null;

    use MVCSettingThemeTrait;
    use MVCPostTypeTrait;
    use MVCTaxonomyTrait;
    use MVCCustomColumnsTrait;
    use MVCMetaBoxesTrait;
    use MVCUserFieldsTrait;
    use MVCAssetsTrait;
    use MVCRoleTrait;
    use MVCShortcodeTrait;
    use MVCMenuTrait;
    use MVCAjaxTrait;
    use MVCHooksTrait;
    use MVCCustomizerTrait;
    use MVCRestApiTrait;
    use MVCSidebarTrait;
    use MVCMetaBoxesCommentsTrait;
    use MVCCronTrait;
    use MVCModelsTrait;
    use MVCMenuAdminPanelTrait;
    use MVCElementorTrait;
    use MVCUrlHandlerTrait;

    const CRON_INTERVAL_MINUTES = "minutes";
    const CRON_INTERVAL_DAILY = "daily";
    const CRON_INTERVAL_WEEKLY = "weekly";
    const CRON_INTERVAL_HOURLY = "hourly";

    function __construct(private $name  = 'MVCTheme') {
        MVCElementorExtension::instance();
    }

    public static function getInstance(): ?MVCTheme
    {
        if (self::$instance == null) {
            self::$instance = new MVCTheme();
        }
        return self::$instance;
    }

    public function initialize(): void
    {

        if (!defined('THEME_URL')) {
            define('THEME_URL', get_template_directory_uri() . "/");
        }

        $this->initRoles();

        add_action('switch_theme', [$this, 'removeRole'], 10, 2);
        add_action('after_switch_theme', [$this, 'switchTheme'], 10, 2);

        add_action('init', [$this, 'init'], 1000);
        add_action('init', [$this, 'registerPostTypes'], 1000);
        add_action('init', [$this, 'registerTaxonomies'], 1000);

        add_action('customize_register', [$this, 'themeCustomize'], 1);


        add_action('wp_nav_menu_item_custom_fields', [$this, 'wpNavMenuItemCustomFields'], 10, 5);
        add_action('wp_update_nav_menu_item', [$this, 'wpUpdateNavMenuItem'], 10, 2);
        add_action('login_head', [$this, 'adminLogo']);
        add_action('elementor/elements/categories_registered', [$this, 'addElementorWidgetCategories']);
        add_action('add_meta_boxes', [$this, 'addMetaBoxesAction']);
        add_action('save_post', [$this, 'saveMetaBoxesAction']);
        add_action('edit_user_profile', [$this, 'showUserField']);
        add_action('edit_user_profile_update', [$this, 'saveUserField']);
        add_action('admin_init', [$this, 'adminInit'], 1000);

        add_filter('upload_mimes', [$this, 'uploadMimes']);
        add_filter('wp_check_filetype_and_ext', [$this, 'fixSvgMimeType'], 10, 5);
        add_filter('wp_prepare_attachment_for_js', [$this, 'showSvgInMediaLibrary']);
        add_filter('login_headerurl', fn() => get_home_url());
        add_filter('login_headertext', fn() => false);
        add_filter('category_link', fn($a) => str_replace('category/', '', $a), 99);


        $this->initializeRestApi();
        $this->initializeSidebars();
        $this->initializeMetaboxComments();
        $this->initializeCron();
        $this->customColumnInit();
        $this->actionHooksInit();
        $this->actionFiltersInit();
        $this->registerModels();
        $this->registerAjaxAction();
        $this->registerMenu();
        $this->initializeMenuAdminPanel();

    }

    function run() {
        $runFile = $this->getThemeChildFilePath("run.php");
        if (is_file($runFile)) {
            include_once $runFile;
        }
    }
}
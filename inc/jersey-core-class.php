<?php

/**
 *
 */
class JerseyCore
{
    public function __construct()
    {
        $this->initClass();
        $this->Model();
        $this->initLibs();
        $this->initPostType();
        $this->initCss();
        $this->initJs();
        $this->suportTheme();

        JerseyRoutes::init();

        $jerseyGallery = new jerseyGallery();
        $jerseyGallery->hearPost();

        $jerseyRating = new JerseyRating();
        $jerseyRating->hearPost();
    }

    private function initCss()
    {
    }
    private function initJs()
    {
    }

    private function initClass()
    {
        require_once JERSEY_DIR . "classes/jersey-flash-messages.php";
        require_once JERSEY_DIR . "classes/jersey-routes.php";
        require_once JERSEY_DIR . "classes/jersey-fb.php";
        require_once JERSEY_DIR . '/inc/view/create-jersey.php';
    }

    private function initLibs()
    {
        require_once JERSEY_DIR . "inc/libs/theme-wrapper.php";
        require      JERSEY_DIR . "inc/libs/meta-box/meta-box.php";
    }

    private function Model()
    {
        require_once JERSEY_DIR . "inc/model/jersey.php";
        require_once JERSEY_DIR . "inc/model/jersey-gallery.php";
        require_once JERSEY_DIR . "inc/model/jersey-rating.php";
        require_once JERSEY_DIR . "inc/model/jersey-user.php";
    }

    private function initPostType()
    {
        require_once JERSEY_DIR . "inc/post-type/jersey.php";
    }

    private function suportTheme()
    {
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');
        //
        // // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
         'primary' => esc_html__('Primary Menu', 'eduma'),
       ));

        function rolIsJersey()
        {
            // var_dump(is_admin());
            // var_dump(!defined('DOING_AJAX'));
            // var_dump(current_user_can('jerseyuser'));
            // var_dump();
            if (is_admin() && !defined('DOING_AJAX') && (current_user_can('jerseyuser')) && !isset($_POST['action'])) {
                wp_redirect(home_url() . '/profile');
                exit;
            }

            if (current_user_can('jerseyuser')) {
                // TODO: TEMPORALMENTE true, despues de realizar el metodo de logout, favor poner en false.
                show_admin_bar(true);
            }
        }
        add_action('init', 'rolIsJersey');
    }
}

<?php

/**
 *
 */
class JerseyCore
{
    public function __construct()
    {
        $this->Model();
        $this->initLibs();
        $this->initPostType();
        $this->initCss();
        $this->initJs();
        $this->suportTheme();
        
        $jerseyGallery = new jerseyGallery();
        $jerseyGallery->hearPost();
    }

    private function initCss()
    {
    }
    private function initJs()
    {
    }

    private function initLibs()
    {
        require_once JERSEY_DIR . "inc/libs/theme-wrapper.php";
    }

    private function Model()
    {
        require_once JERSEY_DIR . "inc/model/jersey.php";
        require_once JERSEY_DIR . "inc/model/jersey-gallery.php";
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
    }
}

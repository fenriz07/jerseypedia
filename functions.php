<?php

define('JERSEY_DIR', trailingslashit(get_template_directory()));
define('JERSEY_URI', trailingslashit(get_template_directory_uri()));
define('JERSEY_DOMAIN_TEXT', 'jeseypedia-theme');

require_once JERSEY_DIR . 'inc/jersey-core-class.php';

if (! function_exists('jersey_setup')) {
    function jersey_setup()
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

        $runJersey = new JerseyCore();
    }
} // jersey_setup
add_action('after_setup_theme', 'jersey_setup');

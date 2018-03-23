<?php

/**
 * Theme wrapper
 * Wrap current template file "into" a wrapper file to avoid code duplication
 *
 * @see http://scribu.net/wordpress/theme-wrappers.html
 */

/**
 * Get the full path to the main template file
 *
 * @return string
 */
function jersey_template_path()
{
    return Jersey_Wrapper::$main_template;
}

/**
 * Get the base name of the template file
 *
 * @return string
 */
function jersey_template_base()
{
    return Jersey_Wrapper::$base;
}

/**
 * Theme wrapper class
 */
class Jersey_Wrapper
{

    /**
     * Stores the full path to the main template file
     */
    public static $main_template;

    /**
     * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
     */
    public static $base;

    /**
     * Wrap current template file "into" a wrapper file
     *
     * @param string $template
     *
     * @return string
     */
    public static function wrap($template)
    {
        self::$main_template = $template;

        self::$base = substr(basename(self::$main_template), 0, -4);

        if ('index' == self::$base) {
            self::$base = false;
        }

        $templates = array('wrapper.php');

        if (self::$base) {
            array_unshift($templates, sprintf('wrapper-%s.php', self::$base));
        }

        return locate_template($templates);
    }
}

add_filter('template_include', array('Jersey_Wrapper', 'wrap'), 99);

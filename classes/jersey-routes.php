<?php
/**
 * Class to manage the jerseypedia routes
 */
class JerseyRoutes
{
    private static $_instance = null;
    private static $routes = [
      'log-in'             => [
                                'file'       => 'templates/auth.php',
                                'permission' => 'not_login'
                              ],
      'sing-up'            => [
                                'file'       => 'templates/register.php',
                                'permission' => 'not_login'
                              ],
      'profile'            => [
                                'file'       => 'templates/profile.php',
                              ],
      'add-jersey'         => [
                                'file'       => 'templates/new-jersey.php',
                                'permission' => 'login'
                              ],
      'logout-jersey'      => [
                                'file'       => 'templates/auth.php',
                                'permission' => 'logout'
                              ],
      'advanced-search'     => [
                                'file'       => 'templates/advanced-search.php',
                              ],
    ];

    public function setRoutes()
    {
        foreach (self::$routes as $key => $route) {
            add_rewrite_rule($key, "index.php?pagename={$key}", 'top');
        }
    }



    private function logout()
    {
        wp_logout();
        JPFlashMessage::FlashMessage(__('See you soon.', JERSEY_DOMAIN_TEXT), 'log-in');
    }

    private function login()
    {
        $login_url = 'log-in';
        if (!is_user_logged_in()) {
            JPFlashMessage::FlashMessage(__('You need to be authenticated to access here.', JERSEY_DOMAIN_TEXT), $login_url);
        }
    }

    private function not_login()
    {
        $profile_url = 'profile';
        if (is_user_logged_in()) {
            JPFlashMessage::FlashMessage(__('You are already authenticated.', JERSEY_DOMAIN_TEXT), $profile_url);
        }
    }

    public function setTemplate($template)
    {
        $pagename = get_query_var('pagename');

        if (array_key_exists($pagename, self::$routes)) {
            $route = self::$routes[$pagename];

            //Apply permission
            if (array_key_exists('permission', $route)) {
                $permission = $route['permission'];
                call_user_func([$this,$permission]);
            }

            $template = locate_template($route['file']);
            return $template;
        }

        return $template;
    }

    public static function init()
    {
        $self = new self();
        add_action('init', array($self,'setRoutes'), 2);
        add_filter('template_include', array($self,'setTemplate'));
    }
}

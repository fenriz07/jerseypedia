<?php


/**
 *
 */
class JPFlashMessage
{
    private static $_instance = null;
    private static $key =  'jerseyMessage';

    private function __construct()
    {
    }

    public static function init()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        if (isset($_SESSION[self::$key]) and $_SESSION[self::$key] != null) {
            // TODO: Llamar al partial para rellenarlo...
            self::showMessage($_SESSION[self::$key]);
            $_SESSION[self::$key] = null;
        }

        return self::$_instance;
    }

    private static function showMessage($message)
    {
        if (is_array($message)) {
            echo '<pre>';
            var_dump($message);
            echo '</pre>';
        } else {
            echo "<h1>{$message}</h1>";
        }
    }

    public function FlashMessage($message, $url=null)
    {
        $url = (is_null($url)) ? $_SERVER['HTTP_REFERER'] : get_site_url() . "/" .$url;

        $_SESSION[self::$key] = $message;

        wp_redirect($url);
        exit();
    }
}

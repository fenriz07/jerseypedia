<?php

/**
 *
 */
class JerseyUser
{
    public function __construct()
    {
        $this->dataUser = $this->getUser();
    }

    private function getUser()
    {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            return $current_user;
        }


        return null;
    }

    public function hearPost()
    {
        add_action('admin_post_nopriv_jersey_new_user', array( $this, 'processingPostRegister' ));
    }

    public function addNewUser($u)
    {
        $user_id = wp_create_user($u['email'], $u['pass'], $u['email']);


        wp_update_user(['ID' => $user_id,'nickname' => $u['name']]);

        $user = new WP_User($user_id);
        $user->set_role('jerseyuser');

        // TODO: Como no tengo servicio para el email se queda pegado.
        //wp_mail($u['email'], 'Welcome!', 'Your Password: ' . $u['pass']);


        JPFlashMessage::FlashMessage(__('User Register', JERSEY_DOMAIN_TEXT));
    }

    public function processingPostRegister()
    {
        check_admin_referer('jersey_new_user', 'jersey_new_user_form');

        $min_character = 8;
        $messages = [];

        $name                = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $email               = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $password            = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $password_confirmed  = filter_input(INPUT_POST, 'password_confirmed', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);

        if (is_null($name)) {
            $messages[] = __('Fullname not defined', JERSEY_DOMAIN_TEXT);
        } elseif (strlen($name) < $min_character) {
            $messages[] = __('Fullname must be greater than 8 characters', JERSEY_DOMAIN_TEXT);
        }

        if ($email == false) {
            $messages[] = __('Mail  Invalid', JERSEY_DOMAIN_TEXT);
        } elseif (is_null($email)) {
            $messages[] = __('Mail not defined', JERSEY_DOMAIN_TEXT);
        }

        if (email_exists($email)) {
            $messages[] = __("The email {$email} is in use", JERSEY_DOMAIN_TEXT);
        }

        if (is_null($password)) {
            $messages[] = __('Password not defined', JERSEY_DOMAIN_TEXT);
        } elseif (strlen($password) < $min_character) {
            $messages[] = __('Password must be greater than 8 characters', JERSEY_DOMAIN_TEXT);
        }

        if (is_null($password_confirmed)) {
            $messages[] = __('Password confirmed not defined', JERSEY_DOMAIN_TEXT);
        }

        if ($password != $password_confirmed) {
            $messages[] = __('Passwords do not match', JERSEY_DOMAIN_TEXT);
        }

        if (count($messages) > 0) {
            JPFlashMessage::FlashMessage($messages);
        }

        $this->addNewUser(['name' => $name, 'email' => $email, 'pass' => $password]);
    }
}

$userjersey = new JerseyUser();
$userjersey->hearPost();

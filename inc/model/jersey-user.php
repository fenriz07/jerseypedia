<?php

require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

/**
 *
 */
class JerseyUser
{
    public function __construct()
    {
        $this->dataUser = $this->getUser();

        $this->metaData = [
          'nickname'    => ['value' =>'','filter'=>FILTER_SANITIZE_STRING],
          'description' => ['value' =>'','filter'=>FILTER_SANITIZE_STRING],
          'ig_jersey'   => ['value' =>'','filter'=>FILTER_SANITIZE_URL],
          'fb_jersey'   => ['value' =>'','filter'=>FILTER_SANITIZE_URL],
          'tw_jersey'   => ['value' =>'','filter'=>FILTER_SANITIZE_URL],
        ];

        add_action('admin_post_nopriv_jersey_new_user', array( $this, 'processingPostRegister' ));
        add_action('admin_post_nopriv_jersey_login', array( $this, 'processingPostLogin' ));
    }

    private function getUser()
    {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            return $current_user;
        }


        return null;
    }

    public function getData()
    {
        $current_user = wp_get_current_user();
        $user_info = get_userdata($current_user->ID);

        $this->metaData['profile_img'] = '';
        foreach ($this->metaData as $key => $value) {
            $this->metaData[$key] = $user_info->{$key};
        }


        return $this->metaData;
    }

    public function setData()
    {
        $current_user = wp_get_current_user();

        foreach ($this->metaData as $key => $data) {
            $filter = filter_input(INPUT_POST, $key, strip_tags($data['filter']), ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
            if (!is_null($filter) && $filter != false) {
                if (!empty(trim($filter))) {
                    update_user_meta($current_user->ID, $key, $filter);
                }
            }
        }


        if (isset($_FILES["profile_img"])) {
            $file  = $_FILES["profile_img"];

            $type  = wp_check_filetype($file['name'])["ext"];

            if ($type == 'jpg' || $type == 'png' || $type == 'jpeg') {
                if ($file['size'] != 0) {
                    $attach_id = media_handle_upload('profile_img', -1);
                    update_user_meta($current_user->ID, 'profile_img', $attach_id);
                }
            }
        }
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

    public function processingPostLogin()
    {
        check_admin_referer('jersey_login', 'jersey_login_form');

        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);

        if ($email == false) {
            $messages[] = __('Mail  Invalid', JERSEY_DOMAIN_TEXT);
        } elseif (is_null($email)) {
            $messages[] = __('Mail not defined', JERSEY_DOMAIN_TEXT);
        }

        if (is_null($password)) {
            $messages[] = __('Password not defined', JERSEY_DOMAIN_TEXT);
        }

        if (!email_exists($email)) {
            $messages[] = __("Email not exists", JERSEY_DOMAIN_TEXT);
        }

        if (count($messages) > 0) {
            JPFlashMessage::FlashMessage($messages);
        }

        $creds = array(
             'user_login'    => $email,
             'user_password' => $password,
             'remember'      => true
         );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            $code_error = $user->get_error_code();
            if ($code_error = 'incorrect_password') {
                JPFlashMessage::FlashMessage('Incorrect password', 'log-in');
            } else {
                JPFlashMessage::FlashMessage('Error', 'log-in');
            }
        } else {
            JPFlashMessage::FlashMessage("Welcome to JerseyPedia", 'profile');
        }
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
new JerseyUser();

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

    public function registerUser()
    {
        if ($this->dataUser == null) {
            JPFlashMessage::FlashMessage(__('Error, you are authenticated', JERSEY_DOMAIN_TEXT));
        }
    }
}

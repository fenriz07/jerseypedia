<?php

/**
 *
 */
class JerseyUser
{
    public function __construct()
    {
        //$this->dataUser = $this->getUser();
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
        add_action('admin_post_nopriv_jersey_new_user', array( $this, 'processingPost' ));
    }

    public function processingPost()
    {
        $min_character = 8;

        //TODO: Modificar Flashmensaje, para que cuando se le envie un arreglo, imprima todos los mensajes de error en lista

        $name                = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $email               = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $password            = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        $password_confirmed  = filter_input(INPUT_POST, 'password_confirmed', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        // if (is_null($name)) {
        //     //Variable no definida
        // }
        // if ($email == false) {
        //     //Email no valido
        // } elseif (is_null($email)) {
        //     //Email no existe
        // }
        // if (is_null($password)) {
        //     //Variable no definida
        // }
        // if (is_null($password_confirmed) {
        //     //Variable no definida
        // }

        // strlen($name) count

        var_dump($name);
    }
}

$userjersey = new JerseyUser();
$userjersey->hearPost();

<?php

/**
 *
 */
class JerseyCore
{
    public function __construct()
    {
        $this->initLibs();
        $this->initModel();
        $this->initCss();
        $this->initJs();
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

    private function initModel()
    {
        require_once JERSEY_DIR . "inc/model/jersey.php";
    }
}

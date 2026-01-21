<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Cookies extends BaseController
{
    /**
     * Enregistre le choix de l'utilisateur
     */
    function accept() {
        helper('cookie');

        set_cookie(
            "acceptcookies",
            "1",
            60 * 60 * 24 * 365, //Le cookie dure 1 an
        );

        return $this->response->setJson(["status" => "ok"]);
    }

    function decline() {
        helper('cookie');

        set_cookie(
            "acceptcookies",
            "0",
            60 * 60 * 24 * 365,
        );

        return $this->response->setJson(["status" => "ok"]);
    }

}
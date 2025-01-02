<?php

namespace controllers;

use controllers\base\WebController;
use utils\Template;

class SampleWebController extends WebController
{
    function home(): string
    {
        return Template::render("views/global/home.php", array("date" => date("d-m-Y à H:i")));
    }

    function exemple($parametre = 'Valeur par défaut'): string
    {
        return "Voilà votre paramètre : $parametre";
    }
}
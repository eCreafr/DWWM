<?php

namespace controllers;

use controllers\base\ApiController;

class SampleApiController extends ApiController
{
    function sample(): string
    {
        return json_encode(array("Ceci est un exemple", "de", "tableau"));
    }
}
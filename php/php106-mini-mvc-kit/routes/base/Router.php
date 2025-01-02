<?php

namespace routes\base;

use routes\Api;
use routes\Cli;
use routes\Web;
use utils\CliUtils;

class Router extends Route
{

    protected Api $api;
    protected Web $web;
    protected Cli $cli;

    function __construct()
    {
        // Register Route
        $this->api = new Api();
        $this->web = new Web();

        // Load CLI Command only if process is start in CLI (not in browser)
        if (!CliUtils::isBrowser()) {
            $this->cli = new Cli();
        }
    }
}

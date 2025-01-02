<?php

namespace controllers\base;

class WebController implements IBase
{
    function redirect($to){
        header("Location: $to");
        die();
    }
}
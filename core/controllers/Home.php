<?php

namespace core\controllers;

use core\classes\Functions;
use core\classes\SendEmail;

class Home
{
    public function index()
    {

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'main',
            'layouts/footer',
            'layouts/html_footer',
        ]);

    }
}
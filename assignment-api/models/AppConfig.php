<?php

//namespace models;

use Phalcon\Mvc\Collection;

class AppConfig extends Collection
{

    public function getSource()
    {
        return "app_config";
    }
}

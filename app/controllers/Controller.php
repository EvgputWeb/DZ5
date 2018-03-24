<?php

require_once APP . '/models/BaseModel.php';
require_once APP . '/views/View.php';


abstract class Controller
{
    protected $model;
    protected $view;

    public function __construct()
    {
        //$this->model = new BaseModel();
        $this->view = new View();
    }
}

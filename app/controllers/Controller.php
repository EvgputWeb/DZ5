<?php

require_once APP . '/models/Model.php';
require_once APP . '/views/View.php';


abstract class Controller
{
    protected $model;
    protected $view;

    public function __construct()
    {
        $this->model = new Model();
        $this->view = new View();
    }
}

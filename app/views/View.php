<?php

class View
{
    public function render($templateName, array $data)
    {
        require_once APP . '/views/' . $templateName . '.php';
    }
}

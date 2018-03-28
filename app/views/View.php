<?php


class View
{
    protected $twig;
    protected $loader;

    public function __construct()
    {
        $this->loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
        $this->twig = new Twig_Environment($this->loader);
    }


    public function render($templateName, array $data)
    {
        // require_once APP . '/views/' . $templateName . '.php';

        echo $this->twig->render($templateName. '.twig', $data);

    }
}

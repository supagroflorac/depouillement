<?php
namespace Depouillement\Views;

abstract class InterfaceView
{
    protected $database = null;
    protected $twig = null;

    abstract protected function compileInfos();

    public function __construct($database)
    {
        $this->database = $database;
        $twigLoader = new \Twig_Loader_Filesystem('twig/');
        $this->twig = new \Twig_Environment($twigLoader);
    }

    public function show()
    {
        $data = $this->compileInfos();
        echo $this->twig->render($this->getTemplateFilename(), $data);
    }

    private function getTemplateFilename()
    {
        $explodedClassName = explode('\\', get_class($this));
        $className = end($explodedClassName);
        return "$className.twig";
    }
}

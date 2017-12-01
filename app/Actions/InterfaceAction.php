<?php
namespace Depouillement\Actions;

use \Exception;
use \Depouillement\Database;

abstract class InterfaceAction
{
    protected $database = null;
    protected $neededParameters = array();

    abstract protected function execute(array $params);

    public function __construct(Database $database, array $get, array $post)
    {
        $this->database = $database;
        $this->get = $get;
        $this->post = $post;
    }

    protected function checkParams()
    {
        $params = array();
        foreach ($this->neededParameters as $neededParameter) {
            if (array_key_exists($neededParameter, $this->get)) {
                $params[$neededParameter] = $this->get[$neededParameter];
                continue;
            }

            if (array_key_exists($neededParameter, $this->post)) {
                $params[$neededParameter] = $this->post[$neededParameter];
                continue;
            }
            throw new Exception("Paramètre manquant ($neededParameter).");
        }
        return $params;
    }

    public function run()
    {
        $this->execute($this->checkParams());
    }
}

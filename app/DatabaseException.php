<?php
namespace Depouillement;

class DatabaseException extends \Exception
{
    public $query;
    public $error;

    public function __construct(string $query, array $error, int $code = 0)
    {
        $this->query = $query;
        $this->error = $error;
        parent::__construct($this->buildMessage($query, $error), $code);
    }

    protected function buildMessage($query, $error)
    {
        return "Erreur pendant l'execution de la requête : \"$query\" : \"$error[2]\"";
    }
}

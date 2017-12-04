<?php
namespace Depouillement;

class Author
{
    public $name;
    public $firstname;

    public function __construct($firstname, $name)
    {
        $this->name = $name;
        $this->firstname = $firstname;
    }
}

<?php
namespace Depouillement\Views;

class ModUser extends InterfaceView
{
    public function __construct($database, $userid)
    {
        parent::__construct($database);
        $this->userid = $userid;
    }

    protected function compileInfos()
    {
        $data = array(
            'user' => $this->database->getUserByID($this->userid),
            'groups' => $this->database->getAllGroups(),
        );
        return $data;
    }
}

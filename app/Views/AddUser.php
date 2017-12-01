<?php
namespace Depouillement\Views;

class AddUser extends InterfaceView
{
    protected function compileInfos()
    {
        $data = array(
            'groups' => $this->database->getAllGroups(),
        );
        return $data;
    }
}

<?php
namespace Depouillement\Views;

class ListGroups extends InterfaceView
{
    protected function compileInfos()
    {
        $data = array(
            'groups' => $this->database->getAllGroups(),
        );
        return $data;
    }
}

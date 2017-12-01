<?php
namespace Depouillement\Views;

class ListUsers extends InterfaceView
{
    protected function compileInfos()
    {
        $data = array(
            'users' => $this->database->getAllUsers(),
        );
        return $data;
    }
}

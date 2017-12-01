<?php
namespace Depouillement\Actions;

class AddGroup extends InterfaceAction
{
    protected $neededParameters = array('group_name');

    protected function execute(array $params)
    {
        $this->database->addGroup($params['group_name']);
    }
}

<?php
namespace Depouillement\Actions;

class DelGroup extends InterfaceAction
{
    protected $neededParameters = array('groupid');

    protected function execute(array $params)
    {
        $this->database->deleteGroup($params['groupid']);
    }
}

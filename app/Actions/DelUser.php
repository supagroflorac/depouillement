<?php
namespace Depouillement\Actions;

class DelUser extends InterfaceAction
{
    protected $neededParameters = array('userid');

    protected function execute(array $params)
    {
        $this->database->deleteUser($params['userid']);
    }
}

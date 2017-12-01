<?php
namespace Depouillement\Actions;

class ModUser extends InterfaceAction
{
    protected $neededParameters = array(
        'groupid',
        'username',
        'userid',
    );

    protected function execute(array $params)
    {
        $this->database->updateUser(
            $params['userid'],
            $params['username'],
            $params['groupid']
        );
    }
}

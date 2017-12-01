<?php
namespace Depouillement\Actions;

class AddUser extends InterfaceAction
{
    protected $neededParameters = array(
        'user_name',
        'id_group',
    );

    protected function execute(array $params)
    {
        $this->database->addUser($params['user_name'], $params['id_group']);
    }
}

<?php
namespace Depouillement\Actions;

class ModGroup extends InterfaceAction
{
    protected $neededParameters = array(
        'groupid',
        'groupName',
    );

    protected function execute(array $params)
    {
        $this->database->updateGroup($params['groupid'], $params['groupName']);
    }
}

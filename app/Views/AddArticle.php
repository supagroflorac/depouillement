<?php
namespace Depouillement\Views;

class AddArticle extends InterfaceView
{
    protected function compileInfos()
    {
        $data = array(
            'groups' => $this->database->getAllGroups(),
            'noGroupUsers' => $this->database->getAllUsersFromGroup('0'),
        );

        foreach ($data['groups'] as $key => $group) {
            $data['groups'][$key]['members'] = $this->database->getAllUsersFromGroup($group['id']);
        }

        return $data;
    }
}

<?php
namespace Depouillement\Views;

class ModArticle extends InterfaceView
{
    public function __construct($database, $articleid)
    {
        parent::__construct($database);
        $this->articleid = $articleid;
    }

    protected function compileInfos()
    {
        $data = array(
            'articleid' => $this->articleid,
            'article' => $this->database->getArticleByID($this->articleid),
            'groups' => $this->getGroupsWithMembersAndInterrest(),
        );
        return $data;
    }

    protected function getGroupsWithMembersAndInterrest()
    {
        $results = array();
        $groups = $this->database->getAllGroups();
        $groups[] = array(
            'name' => 'Sans groupe',
            'id' => 0,
        );
        foreach ($groups as $group) {
            $results[$group['id']] = array(
                'name' => $group['name'],
                'members' => array(),
            );
            $members = $this->database->getAllUsersFromGroup($group['id']);
            foreach ($members as $member) {
                $results[$group['id']]['members'][$member['id']] = array(
                    'name' => $member['name'],
                    'interrested' => $this->database->isInterrested(
                        $member['id'],
                        $this->articleid
                    ),
                );
            }
        }
        return $results;
    }
}

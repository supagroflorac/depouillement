<?php
namespace Depouillement\Views;

class ModGroup extends InterfaceView
{
    public function __construct($database, $groupid)
    {
        parent::__construct($database);
        $this->groupid = $groupid;
    }

    protected function compileInfos()
    {
        $data = array(
            'group' => $this->database->getGroupByID($this->groupid),
        );
        return $data;
    }
}

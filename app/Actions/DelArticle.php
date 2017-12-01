<?php
namespace Depouillement\Actions;

class DelArticle extends InterfaceAction
{
    protected $neededParameters = array('articleid');

    protected function execute(array $params)
    {
        $this->database->deleteArticle($params['articleid']);
    }
}

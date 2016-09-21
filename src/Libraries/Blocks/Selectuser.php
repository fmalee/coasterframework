<?php namespace CoasterCms\Libraries\Blocks;

use CoasterCms\Models\User;
use Auth;

class Selectuser extends Select
{

    public function display($content, $options = [])
    {
        if (is_numeric($content)) {
            $user = User::find($content);
        }
        return !empty($user) && $user->getName() ? $user->getName() : $content;
    }

    public function edit($content)
    {
        $users = [];
        foreach (User::all() as $user) {
            $users[$user->id] = $user->getName() . ' (#'.$user->id.')';
        }
        $this->_editViewData['customName'] = array_key_exists($content, $users) ? '' : $content;
        $this->_editViewData['selectOptions'] = [0 => '-- Custom User --'] + $users;
        $content = $this->_editViewData['customName'] ? 0 : ($content ?: Auth::user()->id);
        return parent::edit($content);
    }

    public function save($content)
    {
        return String_::save($content['custom'] ?: (!empty($content['select']) ? $content['select'] : ''));
    }

    public function generateSearchText($content)
    {
        $userAliases = $content ? User::userAliases() : [];
        $userName = (is_numeric($content) && !empty($userAliases[$content])) ? $userAliases[$content] : $content;
        return $this->_generateSearchText($userName);
    }

}
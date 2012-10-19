<?php


abstract class Controller_Manage extends Controller_Abstract
{
    protected function _before_execute()
    {
        parent::_before_execute();

        $this->_user = Account::find('user_id = ?',$this->_user_id)->getOne();
        if(!in_array($this->_user->user_mail,Q::ini('appini/managers')))
        {
            return $this->msg('请先登陆',url('default::default/signin'));
        }
    }
}